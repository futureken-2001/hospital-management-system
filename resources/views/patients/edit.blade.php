<x-app-layout title="Edit Patient">

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <h3 class="mb-4">Edit Patient — {{ $patient->patient_number }}</h3>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('patients.update', $patient) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <x-input-label for="name" value="Full Name" />
                            <x-text-input id="name" name="name" :value="old('name', $patient->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" />
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-input-label for="age" value="Age" />
                                <x-text-input id="age" type="number" min="0" max="150" name="age" :value="old('age', $patient->age)" required />
                                <x-input-error :messages="$errors->get('age')" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-label for="phone" value="Phone Number" />
                                <x-text-input id="phone" name="phone" :value="old('phone', $patient->phone)" required />
                                <x-input-error :messages="$errors->get('phone')" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <x-input-label for="residence" value="Residence" />
                            <x-text-input id="residence" name="residence" :value="old('residence', $patient->residence)" required />
                            <x-input-error :messages="$errors->get('residence')" />
                        </div>

                        <div class="d-flex gap-2">
                            <x-primary-button>Save Changes</x-primary-button>
                            <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            @can('delete', $patient)
                <div class="card border-danger mt-4">
                    <div class="card-body">
                        <h5 class="card-title text-danger">Delete patient record</h5>
                        <p class="text-muted small">This permanently removes the patient and their appointment history. This cannot be undone.</p>
                        <form method="POST" action="{{ route('patients.destroy', $patient) }}" onsubmit="return confirm('Delete this patient record permanently?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Delete Patient</button>
                        </form>
                    </div>
                </div>
            @endcan

        </div>
    </div>

</x-app-layout>
