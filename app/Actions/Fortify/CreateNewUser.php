<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Aws\Rekognition\Exception\RekognitionException;
use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'foto1' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg'],
            'foto2' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg'],
            'user_type' => ['required', Rule::in(['organizador', 'estudio', 'cliente'])],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

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

        $foto1 = $input['foto1'];
        $foto2 = $input['foto2'];

//        $nombre_con_guion = str_replace(" ", "_", $input['name']);
        $nombre_sin_espacios = trim($input['name']);
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

//                $externalImageId1 = uniqid();


                $indexResult1 = $rekognition->indexFaces([
                    'CollectionId' => $collectionName,
                    'Image' => [
                        'S3Object' => [
                            'Bucket' => env('AWS_BUCKET'),
                            'Name' => 'profile/' . $nombre_con_guion . '/' . $foto2Name,
                        ],
                    ],
//                    'ExternalImageId' => $externalImageId1,
                ]);

//                $indexResult2 = $rekognition->indexFaces([
//                    'CollectionId' => $collectionName,
//                    'Image' => [
//                        'S3Object' => [
//                            'Bucket' => env('AWS_BUCKET'),
//                            'Name' => 'profile/' . $nombre_con_guion . '/' . $foto2Name,
//                        ],
//                    ],
//                    'ExternalImageId' => $externalImageId2,
//                ]);

                $faceRecord = $indexResult1['FaceRecords'][0];
                $faceId = $faceRecord['Face']['FaceId'];

                $user = User::create([
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'password' => Hash::make($input['password']),
                    'user_type' => $input['user_type'],
                    'foto1_url' => $foto1Url,
                    'foto2_url' => $foto2Url,
                    'face_id' => $faceId,
                ]);

                //Verificar porque almaceno las 2 urls en la tabla users
//                $user->fotos()->createMany([
//                    ['url' => $foto1Url],
//                    ['url' => $foto2Url],
//                ]);


                return $user;
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
