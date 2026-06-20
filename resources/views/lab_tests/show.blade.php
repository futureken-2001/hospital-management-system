<x-app-layout title="Lab Test Detail">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h3 class="mb-0">{{ $labTest->test_name }}</h3>
            <span class="text-muted">for {{ $labTest->patient->name }} ({{ $labTest->patient->patient_number }})</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('lab-tests.print', $labTest) }}" target="_blank" class="btn btn-outline-secondary">🖨 Print Request</a>
            @can('updateResult', $labTest)
                <a href="{{ route('lab-tests.edit', $labTest) }}" class="btn btn-primary">Update Status / Result</a>
            @endcan
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Details</h5>
                    <dl class="row mb-0 small">
                        <dt class="col-4">Status</dt>
                        <dd class="col-8">
                            <span class="badge badge-status-{{ $labTest->status }}">{{ str_replace('_', ' ', ucfirst($labTest->status)) }}</span>
                        </dd>

                        <dt class="col-4">Patient</dt>
                        <dd class="col-8"><a href="{{ route('patients.show', $labTest->patient) }}">{{ $labTest->patient->name }}</a></dd>

                        <dt class="col-4">Requested by</dt>
                        <dd class="col-8">Dr. {{ $labTest->doctor->name }}</dd>

                        <dt class="col-4">Requested at</dt>
                        <dd class="col-8">{{ $labTest->requested_at->format('d M Y, H:i') }}</dd>

                        @if($labTest->labTechnician)
                            <dt class="col-4">Handled by</dt>
                            <dd class="col-8">{{ $labTest->labTechnician->name }}</dd>
                        @endif

                        @if($labTest->completed_at)
                            <dt class="col-4">Completed at</dt>
                            <dd class="col-8">{{ $labTest->completed_at->format('d M Y, H:i') }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Result</h5>
                    @if($labTest->result)
                        <p class="mb-0">{{ $labTest->result }}</p>
                    @else
                        <p class="text-muted mb-0">No result has been entered yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
