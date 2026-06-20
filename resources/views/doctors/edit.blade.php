<x-app-layout title="Edit Staff Member">

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <h3 class="mb-4">Edit Staff Member</h3>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <x-input-label for="name" value="Full Name" />
                            <x-text-input id="name" name="name" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="role" value="Role" />
                            <select id="role" name="role" class="form-select" required>
                                <option value="doctor" {{ old('role', $user->role) === 'doctor' ? 'selected' : '' }}>Doctor</option>
                                <option value="lab_technician" {{ old('role', $user->role) === 'lab_technician' ? 'selected' : '' }}>Lab Technician</option>
                                <option value="receptionist" {{ old('role', $user->role) === 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                                <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" />
                        </div>

                        <div class="form-text mb-3">
                            To reset this user's password, ask them to use "Forgot your password?" on the login page, or delete and re-create the account.
                        </div>

                        <div class="d-flex gap-2">
                            <x-primary-button>Save Changes</x-primary-button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
