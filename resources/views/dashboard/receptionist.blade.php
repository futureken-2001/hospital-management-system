<x-app-layout title="Receptionist Dashboard">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Front Desk — {{ now()->format('D, d M Y') }}</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('patients.create') }}" class="btn btn-primary">+ Register Patient</a>
            <a href="{{ route('appointments.create') }}" class="btn btn-outline-primary">+ Add to Queue</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <div class="small text-white-75">Patients Registered Today</div>
                    <div class="fs-2 fw-bold">{{ $stats['patients_registered_today'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-info">
                <div class="card-body">
                    <div class="small text-white-75">Appointments Today</div>
                    <div class="fs-2 fw-bold">{{ $stats['appointments_today'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-secondary">
                <div class="card-body">
                    <div class="small text-white-75">Waiting Now</div>
                    <div class="fs-2 fw-bold">{{ $stats['waiting_now'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Today's queue — all doctors</h5>

            @if($todaysQueue->isEmpty())
                <p class="text-muted mb-0">No appointments have been booked yet today.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-queue">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($todaysQueue as $appointment)
                            <tr>
                                <td class="fw-bold">{{ $appointment->queue_number }}</td>
                                <td>
                                    <a href="{{ route('patients.show', $appointment->patient) }}">{{ $appointment->patient->name }}</a>
                                    <div class="text-muted small">{{ $appointment->patient->patient_number }}</div>
                                </td>
                                <td>Dr. {{ $appointment->doctor->name }}</td>
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
                                        <form method="POST" action="{{ route('appointments.destroy', $appointment) }}" class="d-inline" onsubmit="return confirm('Remove this patient from the queue?');">
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
            @endif
        </div>
    </div>

</x-app-layout>
