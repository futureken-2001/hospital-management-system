<x-app-layout title="Lab Technician Dashboard">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Lab Queue — {{ now()->format('D, d M Y') }}</h3>
        <a href="{{ route('lab-tests.index') }}" class="btn btn-outline-primary">View Full History</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <div class="small">Pending</div>
                    <div class="fs-2 fw-bold">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-info">
                <div class="card-body">
                    <div class="small text-white-75">In Progress</div>
                    <div class="fs-2 fw-bold">{{ $stats['in_progress'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-success">
                <div class="card-body">
                    <div class="small text-white-75">Completed Today</div>
                    <div class="fs-2 fw-bold">{{ $stats['completed_today'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center">
                <span>Tests awaiting action</span>
                <span class="badge bg-secondary" id="lab-live-indicator">live</span>
            </h5>

            @if($tests->isEmpty())
                <p class="text-muted mb-0">No pending or in-progress tests right now — the lab is clear. New requests will appear here instantly.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-queue">
                        <thead>
                        <tr>
                            <th>Requested</th>
                            <th>Patient</th>
                            <th>Test</th>
                            <th>Requested by</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tests as $test)
                            <tr>
                                <td class="small text-muted">{{ $test->requested_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('patients.show', $test->patient) }}">{{ $test->patient->name }}</a>
                                    <div class="text-muted small">{{ $test->patient->patient_number }}</div>
                                </td>
                                <td>{{ $test->test_name }}</td>
                                <td>Dr. {{ $test->doctor->name }}</td>
                                <td>
                                    @if($test->isPending())
                                        <span class="badge badge-status-pending">Pending</span>
                                    @else
                                        <span class="badge badge-status-in_progress">In progress</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('lab-tests.edit', $test) }}" class="btn btn-sm btn-primary">
                                        {{ $test->isPending() ? 'Start' : 'Update result' }}
                                    </a>
                                    <a href="{{ route('lab-tests.print', $test) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Print</a>
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
