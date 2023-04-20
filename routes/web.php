<?php

use App\Http\Controllers\DiskController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PhotographController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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



Route::get('/photographs', [PhotographController::class, 'index'])->name('photographs.index');
Route::post('/photographs', [PhotographController::class, 'upload'])->name('photographs.upload');

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
