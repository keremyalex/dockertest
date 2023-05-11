<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PhotoClient;
use App\Models\User;
use Aws\Rekognition\RekognitionClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PhotoController extends Controller
{
    public function uploadImageCopy(Request $request, $event_id, $album_id)
    {
//        dd($event_id, $album_id);

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
        $originalPath = 'events/'. $event_id . '/' . $album_id . '/originals/' . $imageName;
        $watermarkedPath = 'events/'. $event_id . '/' . $album_id . '/watermarked/' . $imageName;

        Storage::disk('s3')->put($originalPath, file_get_contents($image));
        Storage::disk('s3')->put($watermarkedPath, file_get_contents(public_path('temp/' . $imageName)));

        // Eliminar la imagen con marca de agua del directorio temporal
        unlink(public_path('temp/' . $imageName));

        //Guardar en base de datos
        $photo = new Photo();
        $photo->url = $originalPath;
        $photo->url_water = $watermarkedPath;
        $photo->album_id = $album_id;
        $photo->save();

        //Nuevo codigo para el reconocimiento facial

        // Buscar los usuarios que aparecen en la foto y guardar una relación entre ellos y la foto
        $rekognition = new RekognitionClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $result = $rekognition->detectFaces([
            'Image' => [
                'S3Object' => [
                    'Bucket' => env('AWS_BUCKET'),
                    'Name' => $originalPath,
                ],
            ],
            'MinConfidence' => 70,
        ]);

        $faceDetails = $result['FaceDetails'];


        $result = $rekognition->searchFacesByImage([
            'CollectionId' => 'gente',
            'Image' => [
                'S3Object' => [
                    'Bucket' => env('AWS_BUCKET'),
                    'Name' => $originalPath,
                ],
            ],
        ]);

        $faceMatches = $result['FaceMatches'];

        foreach ($faceMatches as $faceMatch) {
            $user = User::where('face_id', $faceMatch['Face']['FaceId'])->first();

            if ($user) {
                $photoClient = new PhotoClient();
                $photoClient->photo_id = $photo->id;
                $photoClient->user_id = $user->id;
                $photoClient->save();
            }
        }


        return response()->json(['message' => 'Imagen subida y almacenada con éxito en Amazon S3.']);
    }

    public function uploadImage(Request $request, $event_id, $album_id)
    {
//        dd($event_id, $album_id);

        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $image = $request->file('file');
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        // Crear una instancia de la imagen
        $img = Image::make($image);

        // Cargar la marca de agua
        $watermark = Image::make('img/watermark2.png');
//        $watermark = Image::make('img/watermark.png');

        // Redimensionar la marca de agua para que ocupe casi todo el tamaño de la imagen original
        $maxSize = min($img->width(), $img->height()) * 0.8;
        $watermark->resize($maxSize, $maxSize, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Calcular las coordenadas para posicionar la marca de agua en el centro de la imagen original
        $x = ($img->width() - $watermark->width()) / 2;
        $y = ($img->height() - $watermark->height()) / 2;

        // Verificar la orientación de la imagen original
        if ($img->width() > $img->height()) {
            // Imagen horizontal, ajustar la posición vertical de la marca de agua
            $y = $img->height() - $watermark->height();
        }

        // Aplicar la marca de agua en la imagen original
        $img->insert($watermark, 'top-left', (int)$x, (int)$y);

        // Guardar la imagen con marca de agua en un directorio temporal
        $img->save(public_path('temp/' . $imageName));

        // Almacenar la imagen original y la imagen con marca de agua en Amazon S3
        $originalPath = 'events/'. $event_id . '/' . $album_id . '/originals/' . $imageName;
        $watermarkedPath = 'events/'. $event_id . '/' . $album_id . '/watermarked/' . $imageName;

        Storage::disk('s3')->put($originalPath, file_get_contents($image));
        Storage::disk('s3')->put($watermarkedPath, file_get_contents(public_path('temp/' . $imageName)));

        // Eliminar la imagen con marca de agua del directorio temporal
        unlink(public_path('temp/' . $imageName));

        //Guardar en base de datos
        $photo = new Photo();
        $photo->url = $originalPath;
        $photo->url_water = $watermarkedPath;
        $photo->album_id = $album_id;
        $photo->save();

        //Nuevo codigo para el reconocimiento facial

        // Buscar los usuarios que aparecen en la foto y guardar una relación entre ellos y la foto
        $rekognition = new RekognitionClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $result = $rekognition->detectFaces([
            'Image' => [
                'S3Object' => [
                    'Bucket' => env('AWS_BUCKET'),
                    'Name' => $originalPath,
                ],
            ],
            'MinConfidence' => 70,
        ]);

        $faceDetails = $result['FaceDetails'];


        $result = $rekognition->searchFacesByImage([
            'CollectionId' => 'gente',
            'Image' => [
                'S3Object' => [
                    'Bucket' => env('AWS_BUCKET'),
                    'Name' => $originalPath,
                ],
            ],
        ]);

        $faceMatches = $result['FaceMatches'];

        foreach ($faceMatches as $faceMatch) {
            $user = User::where('face_id', $faceMatch['Face']['FaceId'])->first();

            if ($user) {
                $photoClient = new PhotoClient();
                $photoClient->photo_id = $photo->id;
                $photoClient->user_id = $user->id;
                $photoClient->save();
            }
        }


        return response()->json(['message' => 'Imagen subida y almacenada con éxito en Amazon S3.']);
    }
}
