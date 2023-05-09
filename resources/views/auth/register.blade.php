<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="form-group mt-4">
                <label for="user_type">Tipo de usuario</label>
                <div class="flex items-center">
                    <input id="organizer" type="radio" name="user_type" value="organizador" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                    <label for="organizer" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Organizador</label>
                </div>
                <div class="flex items-center mt-1">
                    <input id="photographer" type="radio" name="user_type" value="estudio" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                    <label for="photographer" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Estudio/Fotógrafo</label>
                </div>
            </div>

{{--            fotos--}}

            <div class="mt-4">
                <x-label for="foto1" value="{{ __('First photo') }}" />
                <x-input id="foto1" class="block mt-1 w-full" type="file" name="foto1" :value="old('foto1')" required />
            </div>

            <div class="mt-4">
                <x-label for="foto2" value="{{ __('Second photo') }}" />
                <x-input id="foto2" class="block mt-1 w-full" type="file" name="foto2" :value="old('foto2')" required />
            </div>

{{--            <x-input-error :messages="$errors->get('user_type')" class="mt-2" />--}}

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
