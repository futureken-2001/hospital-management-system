<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Staff management (doctors, lab_technicians, receptionists,
 * other super_admins). Restricted entirely to super_admin via the
 * 'role:super_admin' middleware in routes/web.php.
 */
class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->query('role'), fn ($q, $role) => $q->where('role', $role))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('doctors.index', compact('users'));
    }

    public function create(): View
    {
        return view('doctors.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);
        $user->assignRole($data['role']);

        return redirect()
            ->route('users.index')
            ->with('status', "Account created for {$user->name} ({$user->role}).");
    }

    public function edit(User $user): View
    {
        return view('doctors.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:super_admin,doctor,lab_technician,receptionist'],
        ]);

        $user->update($validated);
        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('users.index')
            ->with('status', 'Staff account updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return back()->with('status', 'Staff account removed.');
    }
}
