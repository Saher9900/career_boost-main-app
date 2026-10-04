{{-- Login form for guests. Wrapped in the guest layout (centred card with the
     Career Booster logo), unlike the app layout used by authenticated pages. --}}
<x-guest-layout>
    <h2 class="text-lg font-semibold text-gray-900">{{ __('Log in to your account') }}</h2>
    <p class="mt-1 text-sm text-gray-500">{{ __('Welcome back. Enter your details to continue.') }}</p>

    {{-- Flash status messages, e.g. "We have emailed your password reset link".
         The :status prop passes the value into the component. --}}
    <x-auth-session-status class="mt-4" :status="session('status')" />

    {{-- POSTs to the login route, handled by AuthenticatedSessionController@store,
         which validates the credentials and starts the session. --}}
    <form method="POST" action="{{ route('login') }}" class="mt-6">
        @csrf {{-- Hidden CSRF token; Laravel rejects the POST without it. --}}

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            {{-- old('email') re-fills the field after a failed attempt so the
                 user does not have to retype it. --}}
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            {{-- Shows validation errors for this field, if any. --}}
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            {{-- Never repopulated with old() — a password should not be echoed back. --}}
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Remember-me checkbox and the forgot-password link sit on one row,
             split to opposite edges. --}}
        <div class="flex items-center justify-between mt-4">
            <!-- Remember Me -->
            {{-- Checkbox sent as "remember": when checked, Laravel issues a
                 long-lived remember cookie so the user stays logged in after
                 the browser closes. --}}
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#138A9E] shadow-sm focus:ring-[#138A9E]" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            {{-- Link to the password reset request page; guarded so the view
                 still renders if password reset routes are disabled. --}}
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-[#138A9E] rounded-md hover:text-[#0E6378] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#138A9E]" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        {{-- Submit button; text goes through __() so it can be translated.
             w-full overrides the component's default inline-flex sizing. --}}
        <x-primary-button class="w-full justify-center mt-6 py-3">
            {{ __('Log in') }}
        </x-primary-button>
    </form>

    {{-- Cross-link for new visitors. Guarded in case registration is disabled. --}}
    @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-gray-500">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" class="font-medium text-[#138A9E] hover:text-[#0E6378]">
                {{ __('Create one') }}
            </a>
        </p>
    @endif
</x-guest-layout>
