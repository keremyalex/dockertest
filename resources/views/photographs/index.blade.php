<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Álbum de Fotografías de: $nameEvent') }}
        </h2>
    </x-slot>

    <div class="container mx-auto bg-yellow-300">
        <div class="text-2xl font-bold my-4">Subir Imágenes</div>
        <form action="{{route('photographs.upload')}}"
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
        </div>

    </div>
</x-app-layout>
