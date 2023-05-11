<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Album') }}  {{auth()->user()->user_type}}
        </h2>
    </x-slot>

{{--    <h1>Hola desde dentro de un album</h1>--}}
{{--    <h2>Evento: {{$album->event_id}}</h2>--}}
{{--    <h2>Album: {{$album->id}}</h2>--}}
    <div class="container mx-auto">
        <div class="container text-3xl font-semibold mx-auto py-3">
            {{$album->name}}
        </div>
        <div class="text-2xl font-bold my-4">Subir Imágenes</div>
        <form action="{{route('photo.uploadImage', ['event_id' => $album->event_id, 'album_id' => $album->id ])}}"
              method="POST"
              class="dropzone"
              id="my-awesome-dropzone"></form>
        <div class="flex justify-end py-5">
            <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" type="submit" onclick="uploadFiles()">Subir imágenes</button>
            <script>
                Dropzone.options.myAwesomeDropzone = {
                    headers:{
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    },
                    autoProcessQueue: false,
                    // otras opciones aquí...
                    dictDefaultMessage: "Arraste una imágen en el recuadro para subirlo",
                    acceptedFiles: "image/*",
                    maxFilesize: 10000000, // 10 MB
                    maxFiles: 10,
                    parallelUploads: 10
                };
                function uploadFiles() {
                    var dropzone = Dropzone.forElement("#my-awesome-dropzone");
                    // dropzone.enqueueFiles(dropzone.getFilesWithStatus(Dropzone.ADDED));
                    dropzone.processQueue();
                    dropzone.on("queuecomplete", function() {
                        setTimeout(function() {
                            dropzone.removeAllFiles();
                        }, 3000);
                    });
                }
            </script>
        </div>

        <div>
            {{--            mostrar las imagenes del evento aqui--}}
            <div class="text-2xl font-bold my-4">Imágenes del album</div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($photos as $photo)
                    <div class="h-auto block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700">
                        <img src="{{$photo->url}}">
                        <div class="flex font-bold text-xl justify-center py-5">
                            <h2>{{$photo->name}}</h2>
                        </div>
                    </div>
                @endforeach
        </div>

    </div>



</x-app-layout>
