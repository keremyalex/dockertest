<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Eventos Sociales') }}</title>

    <!-- Estilos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <!-- Estilos personalizados -->
    <style>
        /* Agrega tus estilos personalizados aquí */
        .slider img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            margin-bottom: 50px;
        }
    </style>
</head>
<body class="antialiased bg-dark text-white">
<div class="container">
    <div class="row mt-5">
        <div class="col d-flex align-items-center mb-3">
            <img src="{{ asset('img/logo3.png') }}" alt="Icono" class="me-3" width="50" height="50">
            <h1 class="mb-0 me-2">Social Event</h1>
            <div class="">
                <button id="downloadButton" class="btn btn-primary inline-block rounded bg-red-400 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                    Descargar Aplicación
                </button>

                <script>
                    const downloadButton = document.getElementById('downloadButton');
                    // const fileUrl = 'https://www.example.com/archivo.pdf'; // Reemplaza con la URL de tu archivo
                    const fileUrl = 'https://drive.google.com/uc?export=download&id=1xgWs_3zDi5m_o3sQ1czdLZ3HEIP3HwA_'; // Reemplaza con la URL de tu archivo

                    downloadButton.addEventListener('click', function() {
                        window.location.href = fileUrl;
                    });
                </script>
            </div>
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="btn btn-secondary">Registrarse</a>
            </div>
        </div>
    </div>

</div>

<div class="container-fluid vh-100 px-md-5">
    <div class="row vh-100 px-2">
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="d-flex flex-column h-100 justify-content-center">
                <h2 style="font-family: 'Courier New', sans-serif;">No lo olvides!</h2>
                <h1 class="display-2 fw-bold">Porque los recuerdos son importantes</h1>
                <h2 style="font-family: 'Courier New', sans-serif;">La plataforma que conecta organizadores de eventos sociales con fotógrafos y estudios para hacer tus eventos únicos e inolvidables</h2>
            </div>
        </div>
        <div class="col-md-6 d-flex flex-column h-100">
            <div class="slider">
                <div><img src="{{ asset('img/slider1.jpg') }}"></div>
                <div><img src="{{ asset('img/slider2.jpg') }}"></div>
                <div><img src="{{ asset('img/slider3.jpg') }}"></div>
                <div><img src="{{ asset('img/slider4.jpg') }}"></div>
            </div>
        </div>
    </div>
</div>



<!-- Librería de jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Librería de Slick -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>

<!-- Scripts personalizados -->
<script>
    $(document).ready(function(){
        $('.slider').slick({
            autoplay: true,
            autoplaySpeed: 2000,
            dots: false,
            arrows: false
        });
    });
</script>

<!-- Scripts de Bootstrap (requeridos para los componentes de JavaScript) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
