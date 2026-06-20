<x-app-layout title="Admin Dashboard">

    <h3 class="mb-4">Admin Dashboard</h3>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <div class="small text-white-75">Total Patients</div>
                    <div class="fs-2 fw-bold">{{ $stats['total_patients'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success">
                <div class="card-body">
                    <div class="small text-white-75">Doctors on Staff</div>
                    <div class="fs-2 fw-bold">{{ $stats['total_doctors'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info">
                <div class="card-body">
                    <div class="small text-white-75">Appointments Today</div>
                    <div class="fs-2 fw-bold">{{ $stats['appointments_today'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <div class="small">Pending Lab Tests</div>
                    <div class="fs-2 fw-bold">{{ $stats['pending_lab_tests'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Quick links</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('patients.index') }}" class="btn btn-outline-primary">Manage Patients</a>
                        <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary">View Daily Queue</a>
                        <a href="{{ route('lab-tests.index') }}" class="btn btn-outline-primary">View Lab Tests</a>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-primary">Manage Staff Accounts</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Lab summary</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            Pending tests <span class="badge bg-warning text-dark">{{ $stats['pending_lab_tests'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Completed tests <span class="badge bg-success">{{ $stats['completed_lab_tests'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
