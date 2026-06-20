<x-app-layout title="My Profile">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h3 class="mb-4">My Profile</h3>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Account information</h5>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <x-input-label for="name" value="Name" />
                            <x-text-input id="name" name="name" :value="old('name', $user->name)" required />
                            <x-input-error :messages="$errors->get('name')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label value="Role" />
                            <input class="form-control" value="{{ str_replace('_', ' ', $user->role) }}" disabled>
                            <div class="form-text">Only a super admin can change your role, from the Staff module.</div>
                        </div>

                        <x-primary-button>Save</x-primary-button>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Update password</h5>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <x-input-label for="current_password" value="Current password" />
                            <x-text-input id="current_password" type="password" name="current_password" autocomplete="current-password" />
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="new_password" value="New password" />
                            <x-text-input id="new_password" type="password" name="password" autocomplete="new-password" />
                            <x-input-error :messages="$errors->updatePassword->get('password')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="new_password_confirmation" value="Confirm new password" />
                            <x-text-input id="new_password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" />
                        </div>

                        <x-primary-button>Update password</x-primary-button>
                    </form>
                </div>
            </div>

            <div class="card border-danger">
                <div class="card-body">
                    <h5 class="card-title text-danger">Delete account</h5>
                    <p class="text-muted small">Once your account is deleted, you will lose access immediately. Ask another super admin to re-create it if this was a mistake.</p>

                    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account?');">
                        @csrf
                        @method('DELETE')

                        <div class="mb-3" style="max-width: 320px;">
                            <x-input-label for="delete_password" value="Confirm password" />
                            <x-text-input id="delete_password" type="password" name="password" />
                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        <button type="submit" class="btn btn-outline-danger">Delete account</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
