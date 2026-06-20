<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Patient Card — {{ $patient->patient_number }}</title>
    @vite(['resources/css/app.scss'])
</head>
<body class="bg-light">

<div class="container py-4">

    <div class="no-print mb-3 d-flex gap-2">
        <button onclick="window.print()" class="btn btn-primary">🖨 Print</button>
        <button onclick="window.close()" class="btn btn-outline-secondary">Close</button>
    </div>

    <div class="card print-card mx-auto" style="max-width: 420px; border: 2px solid #0d6efd;">
        <div class="card-body text-center">
            <h5 class="mb-0">{{ config('app.name', 'Hospital Management System') }}</h5>
            <p class="text-muted small mb-3">Patient Identification Card</p>

            <h2 class="fw-bold text-primary mb-1">{{ $patient->patient_number }}</h2>

            <hr>

            <dl class="row text-start small mb-0">
                <dt class="col-4">Name</dt>
                <dd class="col-8">{{ $patient->name }}</dd>

                <dt class="col-4">Age</dt>
                <dd class="col-8">{{ $patient->age }}</dd>

                <dt class="col-4">Residence</dt>
                <dd class="col-8">{{ $patient->residence }}</dd>

                <dt class="col-4">Phone</dt>
                <dd class="col-8">{{ $patient->phone }}</dd>

                <dt class="col-4">Registered</dt>
                <dd class="col-8">{{ $patient->created_at->format('d M Y') }}</dd>
            </dl>

            <hr>
            <p class="text-muted small mb-0">Please bring this card on every visit.</p>
        </div>
    </div>

</div>

</body>
</html>
