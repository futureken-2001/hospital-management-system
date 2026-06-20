<x-app-layout title="Request Lab Test">

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <h3 class="mb-4">Request Lab Test</h3>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('lab-tests.store') }}">
                        @csrf

                        <div class="mb-3">
                            <x-input-label for="patient_id" value="Patient" />
                            @php
                                $recentPatients = \App\Models\Patient::orderByDesc('created_at')->limit(100)->get();
                                if ($patientId && ! $recentPatients->contains('id', $patientId)) {
                                    $preselected = \App\Models\Patient::find($patientId);
                                    if ($preselected) {
                                        $recentPatients->prepend($preselected);
                                    }
                                }
                            @endphp
                            <select id="patient_id" name="patient_id" class="form-select" required>
                                <option value="">— Select a patient —</option>
                                @foreach($recentPatients as $patient)
                                    <option value="{{ $patient->id }}" {{ (old('patient_id', $patientId) == $patient->id) ? 'selected' : '' }}>
                                        {{ $patient->patient_number }} — {{ $patient->name }} ({{ $patient->residence }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('patient_id')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="test_name" value="Test Name" />
                            <input list="common-tests" id="test_name" name="test_name" class="form-control" value="{{ old('test_name') }}" required placeholder="e.g. Full Blood Count">
                            <datalist id="common-tests">
                                <option value="Full Blood Count"></option>
                                <option value="Malaria Test"></option>
                                <option value="Urinalysis"></option>
                                <option value="Blood Sugar (RBS)"></option>
                                <option value="HIV Test"></option>
                                <option value="Widal Test"></option>
                                <option value="Stool Examination"></option>
                                <option value="Liver Function Test"></option>
                            </datalist>
                            <x-input-error :messages="$errors->get('test_name')" />
                        </div>

                        <div class="form-text mb-3">
                            Every lab technician on duty will be notified instantly — no paper request slip needed.
                        </div>

                        <div class="d-flex gap-2">
                            <x-primary-button>Send Request to Lab</x-primary-button>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
