import './bootstrap';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

/**
 * --------------------------------------------------------------------
 * Notification bell (works for every role)
 * --------------------------------------------------------------------
 * Polls GET /notifications every 15s and rebuilds the dropdown list +
 * unread badge. This is the persistent, "even after a refresh" half
 * of the notification system (the other half is the live toast popup
 * below, fed by Echo broadcasts when Pusher is configured).
 */
function renderNotificationList(notifications) {
    const list = document.getElementById('notifList');
    const badge = document.getElementById('notifBadge');
    const empty = document.getElementById('notifEmpty');

    if (!list || !badge) return;

    const unread = notifications.filter((n) => !n.read_at);

    if (unread.length > 0) {
        badge.textContent = unread.length > 9 ? '9+' : String(unread.length);
        badge.classList.remove('d-none');
    } else {
        badge.classList.add('d-none');
    }

    list.querySelectorAll('.notif-item').forEach((el) => el.remove());

    if (notifications.length === 0) {
        if (empty) empty.classList.remove('d-none');
        return;
    }

    if (empty) empty.classList.add('d-none');

    notifications.slice(0, 8).forEach((n) => {
        const li = document.createElement('li');
        li.className = 'notif-item';

        const isUnread = !n.read_at;
        const message = n.data?.message ?? 'New notification';

        li.innerHTML = `
            <a href="#" class="dropdown-item small d-flex gap-2 ${isUnread ? 'fw-semibold' : 'text-muted'}" data-id="${n.id}">
                <span>${isUnread ? '🔵' : '⚪'}</span>
                <span>${message}</span>
            </a>
        `;

        li.querySelector('a').addEventListener('click', (e) => {
            e.preventDefault();
            markNotificationRead(n.id);
        });

        list.appendChild(li);
    });
}

function fetchNotifications() {
    const bell = document.getElementById('notifBell');
    if (!bell) return; // not authenticated / layout without the bell

    window.axios
        .get('/notifications')
        .then((response) => renderNotificationList(response.data))
        .catch(() => {
            // Silently ignore — bell just won't update this cycle.
        });
}

function markNotificationRead(id) {
    window.axios
        .post(`/notifications/${id}/read`)
        .then(() => fetchNotifications())
        .catch(() => {});
}

document.addEventListener('DOMContentLoaded', () => {
    fetchNotifications();
    setInterval(fetchNotifications, 15000);
});

/**
 * --------------------------------------------------------------------
 * Live toast popup
 * --------------------------------------------------------------------
 * Shared helper used by every role-specific Echo listener below to
 * pop the bottom-right Bootstrap toast immediately when a real-time
 * event arrives, then refresh the bell so the persisted copy shows
 * up too.
 */
function showLiveToast(message) {
    const toastEl = document.getElementById('liveToast');
    const toastBody = document.getElementById('liveToastBody');

    if (!toastEl || !toastBody) return;

    toastBody.textContent = message;

    const toast = window.bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 8000 });
    toast.show();

    fetchNotifications();
}

/**
 * --------------------------------------------------------------------
 * Role-specific real-time channel subscriptions
 * --------------------------------------------------------------------
 * Reads the current user's id/role out of a couple of data-* attrs
 * placed on <body> by the layout (see app-layout.blade.php) so this
 * one bundle works for every role without per-page JS files.
 */
document.addEventListener('DOMContentLoaded', () => {
    if (!window.Echo) return; // Pusher not configured — bell polling above still works.

    const body = document.body;
    const userId = body.dataset.userId;
    const role = body.dataset.userRole;

    if (!userId) return;

    // Doctors (and super_admin, who can monitor any doctor's channel
    // they're authorized for) get popped for BOTH new-patient
    // assignments and completed lab results on their own channel.
    if (role === 'doctor' || role === 'super_admin') {
        window.Echo.private(`doctor.${userId}`)
            .listen('.patient.assigned', (e) => {
                showLiveToast(`🧑‍⚕️ New patient: ${e.patient_name} (Age ${e.age}, ${e.residence}) — queue #${e.queue_number}`);
            })
            .listen('.labtest.completed', (e) => {
                showLiveToast(`🧪 Lab result ready: ${e.test_name} for ${e.patient_name}`);
            });
    }

    // Lab technicians (and super_admin) get popped the instant any
    // doctor orders a new test — this is the "no paper needed" flow.
    if (role === 'lab_technician' || role === 'super_admin') {
        window.Echo.private('lab-technicians')
            .listen('.labtest.requested', (e) => {
                showLiveToast(`🧪 New lab test requested: ${e.test_name} for ${e.patient_name} (Dr. ${e.doctor_name})`);
            });
    }
});
