<x-app-layout title="Doctor Dashboard">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">My Queue — {{ now()->format('D, d M Y') }}</h3>
        <a href="{{ route('lab-tests.create') }}" class="btn btn-primary">+ Request Lab Test</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-bg-secondary">
                <div class="card-body">
                    <div class="small text-white-75">Waiting</div>
                    <div class="fs-2 fw-bold">{{ $stats['waiting'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info">
                <div class="card-body">
                    <div class="small text-white-75">Called</div>
                    <div class="fs-2 fw-bold">{{ $stats['called'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success">
                <div class="card-body">
                    <div class="small text-white-75">Done</div>
                    <div class="fs-2 fw-bold">{{ $stats['done'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <div class="small">Pending Lab Results</div>
                    <div class="fs-2 fw-bold">{{ $stats['pending_lab_tests'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Today's queue</h5>

            @if($queue->isEmpty())
                <p class="text-muted mb-0">No patients in your queue yet today.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Age</th>
                            <th>Residence</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($queue as $appointment)
                            <tr>
                                <td class="fw-bold">{{ $appointment->queue_number }}</td>
                                <td>
                                    <a href="{{ route('patients.show', $appointment->patient) }}">
                                        {{ $appointment->patient->name }}
                                    </a>
                                    <div class="text-muted small">{{ $appointment->patient->patient_number }}</div>
                                </td>
                                <td>{{ $appointment->patient->age }}</td>
                                <td>{{ $appointment->patient->residence }}</td>
                                <td>
                                    @if($appointment->isWaiting())
                                        <span class="badge bg-secondary">Waiting</span>
                                    @elseif($appointment->isCalled())
                                        <span class="badge bg-info">Called</span>
                                    @else
                                        <span class="badge bg-success">Done</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($appointment->nextStatus())
                                        <form method="POST" action="{{ route('appointments.update-status', $appointment) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $appointment->nextStatus() }}">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                @if($appointment->isWaiting()) Call next @else Mark done @endif
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('lab-tests.create', ['patient_id' => $appointment->patient_id]) }}" class="btn btn-sm btn-outline-secondary">
                                        Order lab test
                                    </a>
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
