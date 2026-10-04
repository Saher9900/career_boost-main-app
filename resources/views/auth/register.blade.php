{{-- Registration form for guests. Uses the same guest layout (centred card
     with the Career Booster logo) as the login page for a consistent feel. --}}
<x-guest-layout>
    <h2 class="text-lg font-semibold text-gray-900">{{ __('Create your account') }}</h2>
    <p class="mt-1 text-sm text-gray-500">{{ __('Join Career Booster to find or post opportunities.') }}</p>

    {{-- POSTs to the register route (RegisteredUserController@store), which
         validates the input, creates the user and signs them in. --}}
    <form method="POST" action="{{ route('register') }}" class="mt-6">
        @csrf {{-- Hidden CSRF token; Laravel rejects the POST without it. --}}

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            {{-- old('name') re-fills the field when validation fails. --}}
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            {{-- autocomplete="new-password" tells the browser this is a new
                 password, so it offers to generate one instead of autofilling. --}}
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            {{-- Must match the password field; validated against it by the
                 controller's "confirmed" rule. --}}
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Submit button; text goes through __() so it can be translated.
             w-full overrides the component's default inline-flex sizing. --}}
        <x-primary-button class="w-full justify-center mt-6 py-3">
            {{ __('Register') }}
        </x-primary-button>
    </form>

    {{-- Cross-link back to login for people who already have an account. --}}
    <p class="mt-6 text-center text-sm text-gray-500">
        {{ __('Already registered?') }}
        <a href="{{ route('login') }}" class="font-medium text-[#138A9E] hover:text-[#0E6378]">
            {{ __('Log in') }}
        </a>
    </p>
</x-guest-layout>
