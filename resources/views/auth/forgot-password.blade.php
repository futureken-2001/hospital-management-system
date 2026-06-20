<x-guest-layout>
    <h5 class="card-title mb-3">Forgot your password?</h5>

    <p class="text-muted small">
        No problem. Enter your email and we'll send you a password reset link.
    </p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="d-flex justify-content-end">
            <x-primary-button>Email password reset link</x-primary-button>
        </div>
    </form>
</x-guest-layout>
