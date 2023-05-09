<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear evento') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-validation-errors class="mb-4" />
                    @if(session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-label for="name" class="text-xl" value="{{ __('Nombre del Evento') }}" />
                            <x-input id="name" name="name" class="block mt-1 w-full" type="text" :value="old('name')" required autofocus />
                        </div>

{{--                        <div class="mt-4">--}}
{{--                            <x-label for="client_name" value="{{ __('Nombre del cliente') }}" />--}}
{{--                            <x-input id="client_name" class="block mt-1 w-full opacity-80 cursor-default" type="text" name="client_name" :value="auth()->user()->user_type === 'admin' ? '' : auth()->user()->name" required :readonly="auth()->user()->user_type !== 'admin'" />--}}
{{--                        </div>--}}
                        <div class="mt-4">
                            <x-label for="description" class="text-xl" value="{{ __('Descripción') }}" />
                            <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Escriba una descripción acerca del Evento " :value="old('description')"></textarea>
                        </div>

                        <div class="mt-4">
                            <x-label for="location" class="text-xl" value="{{ __('Ubicación') }}" />
                            <x-input id="location" name="location" class="block mt-1 w-full" type="text" :value="old('location')" required />
                        </div>

                        <div class="mt-4">
                            <x-label for="date" class="text-xl" value="{{ __('Fecha') }}" />
                            <x-input id="date" name="date" class="block mt-1 w-full" type="date" :value="old('date')" required />
                        </div>

                        <div class="mt-4">
                            <x-label for="time" class="text-xl" value="{{ __('Hora') }}" />
                            <x-input id="time" name="time" class="block mt-1 w-full" type="time" :value="old('time')" required />
                        </div>

                        <div class="mt-4">
                            <x-label for="image" class="text-xl" value="{{ __('Imagen') }}" />
                            <x-input id="image" class="text-xl" name="image" class="block mt-1 w-full" type="file" :value="old('time')" required />
{{--                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" type="file">--}}
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG or GIF.</p>
                        </div>



                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-4 text-gray-600 hover:text-gray-800" onclick="history.back()">
                                {{ __('Cancelar') }}
                            </x-button>
                            <x-button class="ml-4">
                                {{ __('Crear evento') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
