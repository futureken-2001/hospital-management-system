<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Lab Request — {{ $labTest->patient->patient_number }}</title>
    @vite(['resources/css/app.scss'])
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="no-print mb-3 d-flex gap-2">
        <button onclick="window.print()" class="btn btn-primary">🖨 Print</button>
        <button onclick="window.close()" class="btn btn-outline-secondary">Close</button>
    </div>

    <div class="card print-card mx-auto" style="max-width: 640px;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="mb-0">{{ config('app.name', 'Hospital Management System') }}</h5>
                    <p class="text-muted small mb-0">Laboratory Test Request Form</p>
                </div>
                <span class="badge badge-status-{{ $labTest->status }} fs-6">{{ str_replace('_', ' ', ucfirst($labTest->status)) }}</span>
            </div>

            <hr>

            <dl class="row small">
                <dt class="col-4">Patient Name</dt>
                <dd class="col-8">{{ $labTest->patient->name }}</dd>

                <dt class="col-4">Patient Number</dt>
                <dd class="col-8">{{ $labTest->patient->patient_number }}</dd>

                <dt class="col-4">Age</dt>
                <dd class="col-8">{{ $labTest->patient->age }}</dd>

                <dt class="col-4">Test Requested</dt>
                <dd class="col-8 fw-bold">{{ $labTest->test_name }}</dd>

                <dt class="col-4">Requested By</dt>
                <dd class="col-8">Dr. {{ $labTest->doctor->name }}</dd>

                <dt class="col-4">Requested At</dt>
                <dd class="col-8">{{ $labTest->requested_at->format('d M Y, H:i') }}</dd>
            </dl>

            <hr>

            <p class="small fw-semibold mb-1">Result:</p>
            <div style="min-height: 100px; border: 1px solid #dee2e6; padding: 0.75rem; border-radius: 0.25rem;">
                {{ $labTest->result ?? '' }}
            </div>

            <div class="row mt-4 small">
                <div class="col-6">
                    <p class="mb-5">Lab Technician Signature: ____________________</p>
                </div>
                <div class="col-6">
                    <p class="mb-5">Date: ____________________</p>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
