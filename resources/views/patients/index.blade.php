<x-app-layout title="Patients">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Patients</h3>
        @can('create', \App\Models\Patient::class)
            <a href="{{ route('patients.create') }}" class="btn btn-primary">+ Register Patient</a>
        @endcan
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('patients.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-1">Search by name, patient number, or phone</label>
                    <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="e.g. P-0001 or Jane Doe">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Registered on</label>
                    <input type="date" name="date" value="{{ $date }}" class="form-control">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-grow-1">Filter</button>
                    @if($q || $date)
                        <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($patients->isEmpty())
                <p class="text-muted mb-0">No patients match your search.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                        <tr>
                            <th>Patient #</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Residence</th>
                            <th>Phone</th>
                            <th>Registered</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($patients as $patient)
                            <tr>
                                <td class="fw-semibold">{{ $patient->patient_number }}</td>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->age }}</td>
                                <td>{{ $patient->residence }}</td>
                                <td>{{ $patient->phone }}</td>
                                <td class="small text-muted">{{ $patient->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    @can('update', $patient)
                                        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $patients->links() }}
                </div>
            @endif
        </div>
    </div>

</x-app-layout>
