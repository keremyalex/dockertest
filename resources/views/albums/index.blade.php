<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Album') }}  {{auth()->user()->user_type}}
        </h2>
    </x-slot>

    <div class="container mx-auto flex justify-between py-3 items-center">
        <div class="container text-3xl font-semibold mx-auto py-3">
            Lista de Albums
        </div>

        @if(auth()->user()->user_type === 'estudio')
            <form method="POST" action="{{route('album.create', ['event_id' => $event->id])}}">
                @csrf
                <div class="flex gap-4">
                    <label>Nuevo album:</label>
                    <x-input name="name" type="text" class="rounded-lg" required/>
                    <button type="submit"
                            class="rounded bg-green-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white">
                        Crear Album
                    </button>
                </div>
            </form>
        @endif

    </div>


    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($albums as $album)
                <a href="{{route('album.show', ['event_id' => $event->id, 'album_id' => $album->id])}}">
                    <div
                        class="h-auto block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700">

                        <img src="{{asset('img/folder.png')}}">
                        <div class="flex font-bold text-xl justify-center py-5">
                            <h2>{{$album->name}}</h2>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>


</x-app-layout>
