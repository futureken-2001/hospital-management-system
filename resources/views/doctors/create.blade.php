<x-app-layout title="Add Staff Member">

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <h3 class="mb-4">Add Staff Member</h3>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <div class="mb-3">
                            <x-input-label for="name" value="Full Name" />
                            <x-text-input id="name" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="role" value="Role" />
                            <select id="role" name="role" class="form-select" required>
                                <option value="">— Select a role —</option>
                                <option value="doctor" {{ old('role') === 'doctor' ? 'selected' : '' }}>Doctor</option>
                                <option value="lab_technician" {{ old('role') === 'lab_technician' ? 'selected' : '' }}>Lab Technician</option>
                                <option value="receptionist" {{ old('role') === 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" />
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-input-label for="password" value="Password" />
                                <x-text-input id="password" type="password" name="password" required />
                                <x-input-error :messages="$errors->get('password')" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-input-label for="password_confirmation" value="Confirm Password" />
                                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required />
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <x-primary-button>Create Account</x-primary-button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
