<x-app-layout title="Register Patient">

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <h3 class="mb-4">Register New Patient</h3>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('patients.store') }}">
                        @csrf

                        <div class="mb-3">
                            <x-input-label for="name" value="Full Name" />
                            <x-text-input id="name" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" />
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-input-label for="age" value="Age" />
                                <x-text-input id="age" type="number" min="0" max="150" name="age" :value="old('age')" required />
                                <x-input-error :messages="$errors->get('age')" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-label for="phone" value="Phone Number" />
                                <x-text-input id="phone" name="phone" :value="old('phone')" required />
                                <x-input-error :messages="$errors->get('phone')" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <x-input-label for="residence" value="Residence" />
                            <x-text-input id="residence" name="residence" :value="old('residence')" required />
                            <x-input-error :messages="$errors->get('residence')" />
                        </div>

                        <div class="form-text mb-3">
                            A unique patient number (e.g. P-0001) will be generated automatically once you save.
                        </div>

                        <div class="d-flex gap-2">
                            <x-primary-button>Register Patient</x-primary-button>
                            <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
