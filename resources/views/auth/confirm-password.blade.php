<x-guest-layout>
    <h5 class="card-title mb-3">Confirm password</h5>

    <p class="text-muted small">
        This is a protected area. Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" type="password" name="password" required autofocus autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="d-flex justify-content-end">
            <x-primary-button>Confirm</x-primary-button>
        </div>
    </form>
</x-guest-layout>
