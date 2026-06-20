<x-app-layout title="Daily Queue">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Daily Queue</h3>
        @can('create', \App\Models\Appointment::class)
            <a href="{{ route('appointments.create') }}" class="btn btn-primary">+ Add to Queue</a>
        @endcan
    </div>

    <form method="GET" action="{{ route('appointments.index') }}" class="row g-2 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label small text-muted mb-1">Date</label>
            <input type="date" name="date" value="{{ $date }}" class="form-control" onchange="this.form.submit()">
        </div>
    </form>

    @if($appointments->isEmpty())
        <div class="card">
            <div class="card-body text-muted">
                No appointments booked for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}.
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($doctors as $doctor)
                @php($doctorAppointments = $appointments->get($doctor->id, collect()))
                @if($doctorAppointments->isNotEmpty())
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header bg-white fw-semibold">
                                Dr. {{ $doctor->name }}
                                <span class="badge bg-secondary float-end">{{ $doctorAppointments->count() }} today</span>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm align-middle mb-0 table-queue">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Patient</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($doctorAppointments as $appointment)
                                        <tr>
                                            <td class="fw-bold">{{ $appointment->queue_number }}</td>
                                            <td>
                                                <a href="{{ route('patients.show', $appointment->patient) }}">{{ $appointment->patient->name }}</a>
                                            </td>
                                            <td>
                                                @if($appointment->isWaiting())
                                                    <span class="badge badge-status-waiting">Waiting</span>
                                                @elseif($appointment->isCalled())
                                                    <span class="badge badge-status-called">Called</span>
                                                @else
                                                    <span class="badge badge-status-done">Done</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($appointment->isWaiting())
                                                    <form method="POST" action="{{ route('appointments.destroy', $appointment) }}" class="d-inline" onsubmit="return confirm('Remove from queue?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

</x-app-layout>
