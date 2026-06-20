<x-app-layout title="Lab Tests">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Lab Tests</h3>
        @can('create', \App\Models\LabTest::class)
            <a href="{{ route('lab-tests.create') }}" class="btn btn-primary">+ Request Lab Test</a>
        @endcan
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('lab-tests.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In progress</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($labTests->isEmpty())
                <p class="text-muted mb-0">No lab tests found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                        <tr>
                            <th>Requested</th>
                            <th>Patient</th>
                            <th>Test</th>
                            <th>Doctor</th>
                            <th>Lab Tech</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($labTests as $test)
                            <tr>
                                <td class="small text-muted">{{ $test->requested_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('patients.show', $test->patient) }}">{{ $test->patient->name }}</a>
                                    <div class="text-muted small">{{ $test->patient->patient_number }}</div>
                                </td>
                                <td>{{ $test->test_name }}</td>
                                <td>Dr. {{ $test->doctor->name }}</td>
                                <td>{{ $test->labTechnician->name ?? '—' }}</td>
                                <td>
                                    <span class="badge badge-status-{{ $test->status }}">{{ str_replace('_', ' ', ucfirst($test->status)) }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('lab-tests.show', $test) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    @can('updateResult', $test)
                                        <a href="{{ route('lab-tests.edit', $test) }}" class="btn btn-sm btn-outline-secondary">Update</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $labTests->links() }}
                </div>
            @endif
        </div>
    </div>

</x-app-layout>
