<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PhotographController extends Controller
{
    public function index()
    {
        return view('photographs.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png.jpg'
        ]);
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);

        return redirect()->route('photographs.index');
    }

    public function watermark(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png.jpg'
        ]);
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);
//        dd($filename);
        return redirect()->route('photographs.index');
    }

    public function watermark2(UploadedFile $file, $filename)
    {
        $image = Image::make(public_path('img/watermark.png'));
        $watermark = Image::make(public_path('img/watermark.png'));
        $file->insert($watermark, 'bottom-right', 10, 10);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $image = $request->file('file');
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        // Crear una instancia de la imagen
        $img = Image::make($image);

        // Cargar la marca de agua
        $watermark = Image::make('img/watermark.png');

        // Aplicar la marca de agua en la imagen original
        $img->insert($watermark, 'bottom-right', 10, 10);

        // Guardar la imagen con marca de agua en un directorio temporal
        $img->save(public_path('temp/' . $imageName));

        // Almacenar la imagen original y la imagen con marca de agua en Amazon S3
        $originalPath = 'originals/' . $imageName;
        $watermarkedPath = 'watermarked/' . $imageName;

        Storage::disk('s3')->put($originalPath, file_get_contents($image));
        Storage::disk('s3')->put($watermarkedPath, file_get_contents(public_path('temp/' . $imageName)));

        // Eliminar la imagen con marca de agua del directorio temporal
        unlink(public_path('temp/' . $imageName));

        return response()->json(['message' => 'Imagen subida y almacenada con éxito en Amazon S3.']);
    }



}
