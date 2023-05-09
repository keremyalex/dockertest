<?php

namespace App\Http\Controllers;

use App\Models\User;
use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ApiRegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'image1' => 'required|file|image|mimes:jpeg,png,jpg',
            'image2' => 'required|file|image|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        //Configuracion del AWS S3
        $s3 = new S3Client([
            'version' => 'latest',
            'region' => 'us-east-1',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        //Configuracion del AWS Rekognition
        $rekognition = new RekognitionClient([
            'version' => 'latest',
            'region' => 'us-east-1',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $foto1 = $request->file('image1');
        $foto2 = $request->file('image2');

        $nombre_sin_espacios = trim($request->name);
        $nombre_con_guion = preg_replace('/\s+/', '_', $nombre_sin_espacios);

        try {
            $foto1Name = uniqid() . '.' . $foto1->getClientOriginalExtension();
            $s3->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => 'profile/'. $nombre_con_guion .'/'. $foto1Name,
                'SourceFile' => $foto1,
            ]);

//            $foto1Url = $s3->getObjectUrl(env('AWS_BUCKET'), $foto1Name);

            $foto1Url = $s3->getObjectUrl(env('AWS_BUCKET'), 'profile/' . $nombre_con_guion . '/' . $foto1Name);

            $foto2Name = uniqid() . '.' . $foto2->getClientOriginalExtension();
            $s3->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => 'profile/'. $nombre_con_guion .'/'. $foto2Name,
                'SourceFile' => $foto2,
            ]);

//            $foto2Url = $s3->getObjectUrl(env('AWS_BUCKET'), $foto2Name);

            $foto2Url = $s3->getObjectUrl(env('AWS_BUCKET'), 'profile/' . $nombre_con_guion . '/' . $foto2Name);

            $result = $rekognition->compareFaces([
                'SimilarityThreshold' => 80,
                'SourceImage' => [
                    'S3Object' => [
                        'Bucket' => env('AWS_BUCKET'),
                        'Name' => 'profile/' . $nombre_con_guion . '/' . $foto1Name,
                    ],
                ],
                'TargetImage' => [
                    'S3Object' => [
                        'Bucket' => env('AWS_BUCKET'),
                        'Name' => 'profile/' . $nombre_con_guion . '/' . $foto2Name,
                    ],
                ],
            ]);


            if (!empty($result['FaceMatches'])) {

                $collectionName = 'gente';
                $collections = $rekognition->listCollections();

                //Verifica si ya existe una collection con el nombre de $collectionName
                if (in_array($collectionName, $collections['CollectionIds'])) {
                    // La colección ya existe
                } else {
                    // La colección no existe, crearla
                    $collection = $rekognition->createCollection([
                        'CollectionId' => $collectionName,
                    ]);
                }



                $indexResult1 = $rekognition->indexFaces([
                    'CollectionId' => $collectionName,
                    'Image' => [
                        'S3Object' => [
                            'Bucket' => env('AWS_BUCKET'),
                            'Name' => 'profile/' . $nombre_con_guion . '/' . $foto2Name,
                        ],
                    ],
                ]);


                $faceRecord = $indexResult1['FaceRecords'][0];
                $faceId = $faceRecord['Face']['FaceId'];

                $userData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password,
                    'foto1_url' => $foto1Url,
                    'foto2_url' => $foto2Url,
                ];

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'user_type' => 'cliente',
                    'foto1_url' => $foto1Url,
                    'foto2_url' => $foto2Url,
                    'face_id' => $faceId,
                ]);


                return response()->json(['message' => 'User registered successfully', 'user' => $userData], 200);
            } else {
                $s3->deleteObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => 'profile/' . $nombre_con_guion . '/' . $foto1Name,
                ]);

                $s3->deleteObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => 'profile/' . $nombre_con_guion . '/' . $foto2Name,
                ]);
                throw ValidationException::withMessages([
                    'foto1' => __('Las fotos no coinciden.'),
                ]);

            }

        }catch (\Exception $e) {
            $s3->deleteObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => 'profile/' . $nombre_con_guion . '/' . $foto1Name,
            ]);

            $s3->deleteObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => 'profile/' . $nombre_con_guion . '/' . $foto2Name,
            ]);

            throw $e;

        }
    }
}
