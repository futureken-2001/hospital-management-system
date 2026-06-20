<x-app-layout title="Patient Record">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h3 class="mb-0">{{ $patient->name }}</h3>
            <span class="text-muted">{{ $patient->patient_number }}</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('patients.print-card', $patient) }}" target="_blank" class="btn btn-outline-secondary">🖨 Print Card</a>
            @can('update', $patient)
                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-outline-primary">Edit</a>
            @endcan
            @can('create', \App\Models\Appointment::class)
                <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary">+ Add to Queue</a>
            @endcan
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Patient Information</h5>
                    <dl class="row mb-0 small">
                        <dt class="col-5">Patient #</dt>
                        <dd class="col-7">{{ $patient->patient_number }}</dd>

                        <dt class="col-5">Age</dt>
                        <dd class="col-7">{{ $patient->age }}</dd>

                        <dt class="col-5">Residence</dt>
                        <dd class="col-7">{{ $patient->residence }}</dd>

                        <dt class="col-5">Phone</dt>
                        <dd class="col-7">{{ $patient->phone }}</dd>

                        <dt class="col-5">Registered</dt>
                        <dd class="col-7">{{ $patient->created_at->format('d M Y, H:i') }}</dd>

                        @if($patient->createdBy)
                            <dt class="col-5">Registered by</dt>
                            <dd class="col-7">{{ $patient->createdBy->name }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            @if($patient->auditLogs->isNotEmpty())
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Audit Trail</h5>
                        <ul class="list-unstyled small mb-0" style="max-height: 280px; overflow-y: auto;">
                            @foreach($patient->auditLogs->sortByDesc('created_at') as $log)
                                <li class="border-bottom py-2">
                                    <span class="badge bg-light text-dark text-capitalize">{{ $log->action }}</span>
                                    by {{ $log->user->name ?? 'System' }}
                                    <div class="text-muted">{{ $log->created_at->format('d M Y, H:i') }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Appointment History</h5>

                    @if($patient->appointments->isEmpty())
                        <p class="text-muted mb-0">No appointments recorded yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Doctor</th>
                                    <th>Queue #</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($patient->appointments->sortByDesc('appointment_date') as $appointment)
                                    <tr>
                                        <td>{{ $appointment->appointment_date->format('d M Y') }}</td>
                                        <td>Dr. {{ $appointment->doctor->name }}</td>
                                        <td>{{ $appointment->queue_number }}</td>
                                        <td>
                                            @if($appointment->isWaiting())
                                                <span class="badge badge-status-waiting">Waiting</span>
                                            @elseif($appointment->isCalled())
                                                <span class="badge badge-status-called">Called</span>
                                            @else
                                                <span class="badge badge-status-done">Done</span>
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

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0">Lab Tests</h5>
                        @can('create', \App\Models\LabTest::class)
                            <a href="{{ route('lab-tests.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary">+ Request Test</a>
                        @endcan
                    </div>

                    @if($patient->labTests->isEmpty())
                        <p class="text-muted mb-0">No lab tests recorded yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                <tr>
                                    <th>Test</th>
                                    <th>Requested by</th>
                                    <th>Status</th>
                                    <th>Result</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($patient->labTests->sortByDesc('requested_at') as $test)
                                    <tr>
                                        <td>
                                            <a href="{{ route('lab-tests.show', $test) }}">{{ $test->test_name }}</a>
                                        </td>
                                        <td>Dr. {{ $test->doctor->name }}</td>
                                        <td>
                                            <span class="badge badge-status-{{ $test->status }}">{{ str_replace('_', ' ', ucfirst($test->status)) }}</span>
                                        </td>
                                        <td class="small">{{ $test->result ? \Illuminate\Support\Str::limit($test->result, 60) : '—' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
