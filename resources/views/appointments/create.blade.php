<x-app-layout title="Add to Queue">

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <h3 class="mb-4">Assign Patient to Doctor's Queue</h3>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('appointments.store') }}">
                        @csrf

                        <div class="mb-3">
                            <x-input-label for="patient_search" value="Find Patient" />
                            <input type="text" id="patient_search" class="form-control" placeholder="Type a patient name or number to search...">
                            <div class="form-text">Start typing, then pick the patient below. New patient? <a href="{{ route('patients.create') }}">Register them first</a>.</div>
                        </div>

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
                            <x-input-label for="doctor_id" value="Doctor" />
                            <select id="doctor_id" name="doctor_id" class="form-select" required>
                                <option value="">— Select a doctor —</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('doctor_id')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="appointment_date" value="Appointment Date" />
                            <x-text-input id="appointment_date" type="date" name="appointment_date" :value="old('appointment_date', now()->toDateString())" required />
                            <x-input-error :messages="$errors->get('appointment_date')" />
                        </div>

                        <div class="form-text mb-3">
                            The queue number for this doctor will be assigned automatically and resets each new day.
                        </div>

                        <div class="d-flex gap-2">
                            <x-primary-button>Add to Queue</x-primary-button>
                            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Simple client-side filter on the patient <select> as the
        // receptionist types in the search box above it — no extra
        // package needed for a list of a few hundred patients.
        document.getElementById('patient_search')?.addEventListener('input', function (e) {
            const term = e.target.value.toLowerCase();
            const select = document.getElementById('patient_id');
            Array.from(select.options).forEach((opt) => {
                if (!opt.value) return;
                opt.hidden = !opt.textContent.toLowerCase().includes(term);
            });
        });
    </script>
    @endpush

</x-app-layout>
