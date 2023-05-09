<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Eventos') }}  {{auth()->user()->user_type}}
        </h2>
    </x-slot>

    <div class="container mx-auto flex justify-between py-3 items-center">
        <div class="container text-3xl font-semibold mx-auto py-3">
            Lista de Eventos
        </div>

        @if(auth()->user()->user_type === 'organizador')
            <form method="GET" action="{{ route('events.create') }}">
                <button type="submit"
                        class="rounded bg-green-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white">
                    Crear evento
                </button>
            </form>
        @endif

    </div>

    <div class="container mx-auto bg-yellow-300">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            @if($events->isEmpty())
                <h1>Hola</h1>
            @else
                @foreach ($events as $event)
                    <div class="h-auto block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700">
                        <a href="{{route('album.index', ['event_id' => $event->id])}}">
                            <img class="rounded-t-lg" src="{{ $event->image }}" alt=""/>
                        </a>
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <h5 class="mb-2 text-2xl font-bold leading-tight text-neutral-800 dark:text-neutral-50">
                                    {{ $event['name'] }}
                                </h5>
                                <form action="{{route('events.show', ['event_id' => $event->id])}}">
                                    @csrf
                                    <button type="submit" class="bg-green-200 border-2 text-lg rounded-lg p-3" data-bs-toggle="modal" data-bs-target="#vertically-centered">QR</button>
                                </form>
                            </div>
                            <div class="h-20">
                                <p class="line-clamp-3 mb-4 text-base text-neutral-600 dark:text-neutral-200">
                                    {{ $event['description'] }}
                                </p>
                            </div>

                            <label class="text-lg font-medium">Ubicación:</label>
                            <p class="mb-4 text-base text-neutral-600 dark:text-neutral-200">
                                {{ $event['location'] }}
                            </p>
                            <label class="text-lg font-medium">Fecha:</label>
                            <p class="mb-4 text-base text-neutral-600 dark:text-neutral-200">
                                {{ $event['date'] }}    {{ $event['time'] }}
                            </p>



                            @if(auth()->user()->user_type === 'estudio')
                                <form method="POST" action="{{ route('suscription.create', [ 'event_id' =>  $event->id]) }}" enctype="multipart/form-data">
                                    <h1>{{$event->id}}</h1>
                                    @csrf
                                    <div class="flex justify-end">
                                        @if($event->subscription == true)
                                            <button type="submit" class="inline-block rounded bg-red-400 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                                                    data-te-ripple-init
                                                    data-te-ripple-color="light">
                                                Suscrito
                                            </button>
                                        @else
                                            <button type="submit" class="inline-block rounded bg-blue-500 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                                                    data-te-ripple-init
                                                    data-te-ripple-color="light">
                                                Suscribirse
                                            </button>

                                        @endif

                                    </div>
                                </form>
                            @endif

                            @if($event->sub == true)
                                <h1>Estoy subscrito</h1>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif


        </div>
    </div>

{{--    <div class="modal fade" id="vertically-centered" tabindex="-1" role="dialog" aria-labelledby="vertically-centered-title" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-dialog-centered">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title" id="vertically-centered-title">Vertically centered</h5>--}}
{{--                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>--}}
{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>--}}
{{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}




</x-app-layout>

