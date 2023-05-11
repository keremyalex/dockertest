<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\DiskController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PhotographController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SuscriptionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
//QR
Route::get('/events/{event_id}', [EventController::class, 'show'])->name('events.show');



Route::get('/photographs', [PhotographController::class, 'index'])->name('photographs.index');
Route::post('/photographs', [PhotographController::class, 'upload'])->name('photographs.upload');
//Route::post('/photographs', [PhotographController::class, 'uploadImage2'])->name('photographs.upload');
Route::post('/photographs', [PhotographController::class, 'watermark'])->name('photographs.watermark');


Route::get('/suscription/index/{event_id}', [SuscriptionController::class, 'index'])->name('suscription.index');
Route::post('/suscription/create/{event_id}', [SuscriptionController::class, 'create'])->name('suscription.create');

//Album
Route::get('/event_{event_id}/album/index', [AlbumController::class, 'index'])->name('album.index');
//Route::post('/album/index/{event_id}/create', [AlbumController::class, 'create'])->name('album.create');
Route::post('/event_{event_id}/album/index/create', [AlbumController::class, 'create'])->name('album.create');
Route::get('/event_{event_id}/album/show/{album_id}', [AlbumController::class, 'show'])->name('album.show');

//Photograph
Route::post('event_{event_id}/album_{album_id}/photograph/create', [PhotoController::class, 'uploadImage'])->name('photo.uploadImage');





//Test Disk S3
Route::get('/disk', [DiskController::class, 'index'])->name('disk.index');

Route::get('/crear-carpeta-s3', function () {
    $folderName = 'events/test.txt';
    $result = Storage::disk('s3')->put($folderName, 'Hola Mundo');
//    $result = Storage::disk('s3')->put('/events','');
    if ($result) {
        return 'Carpeta creada correctamente en S3';
    } else {
        return 'Error al crear la carpeta en S3';
    }
});
//Test Image Water
Route::post('/upload', [PhotographController::class, 'uploadImage'])->name('uploadImage');


Route::get('/watermark', function () {
    $image = Image::make(public_path('img/admin/foto1.jpg'));

    $watermark = Image::make(public_path('img/watermark.png'))->opacity(50);
    $image->insert($watermark, 'center');

    return $image->response('jpg');
});
Route::get('/watermark2', function () {
    $image = Image::make(public_path('img/admin/foto1.jpg'));

    // Obtener las dimensiones de la imagen original
    $width = $image->width();
    $height = $image->height();

    // Verificar si la imagen supera los 800 píxeles en su dimensión más larga
    if (max($width, $height) > 800) {
        // Redimensionar la imagen a un tamaño más pequeño manteniendo su aspecto
        $image->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    }

    $watermark = Image::make(public_path('img/watermark2.png'));

    // Redimensionar la marca de agua para ajustarla dentro de la imagen redimensionada
    $maxSize = min($image->width(), $image->height()) * 0.8;
    $watermark->resize($maxSize, $maxSize, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });

    // Calcular las coordenadas para posicionar la marca de agua en el centro de la imagen original
    $x = ($width - $watermark->width()) / 2;
    $y = ($height - $watermark->height()) / 2;

    // Verificar la orientación de la imagen original
    if ($width > $height) {
        // Imagen horizontal, ajustar la posición vertical de la marca de agua
        $y = $height - $watermark->height();
    }

    // Aplicar la marca de agua en la imagen original
    $image->insert($watermark, 'top-left', (int)$x, (int)$y);

    return $image->response('jpg');
});

Route::get('/unique', function () {
    $unique = uniqid();
    return $unique;
});

