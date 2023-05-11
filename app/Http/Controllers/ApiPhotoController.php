<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PhotoClient;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApiPhotoController extends Controller
{
    public function getCurrentUser(){

        $token = Auth::user()->currentAccessToken();
        $user = $token->tokenable;

        $id = $user->id;

//        $photos_client = PhotoClient::where('user_id', $id)
//            ->whereNotExists(function ($query) {
//                $query->select(DB::raw(1))
//                    ->from('purchases')
//                    ->whereColumn('photo_client_id', 'photos_client.id');
//            })
//            ->get()
//            ->makeHidden(['created_at', 'updated_at']);
        $photos_client = PhotoClient::select('photos_client.*', 'events.name', 'events.date')
            ->join('photos', 'photos.id', '=', 'photos_client.photo_id')
            ->join('albums', 'albums.id', '=', 'photos.album_id')
            ->join('events', 'events.id', '=', 'albums.event_id')
            ->where('photos_client.user_id', $id)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('purchases')
                    ->whereColumn('photo_client_id', 'photos_client.id');
            })
            ->get()
            ->makeHidden(['created_at', 'updated_at']);

//        $purchased_photos = DB::table('purchases')
//            ->join('photos_client', 'purchases.photo_client_id', '=', 'photos_client.id')
//            ->join('photos', 'photos_client.photo_id', '=', 'photos.id')
//            ->select('photos_client.id', 'photos_client.photo_id', 'photos_client.user_id', 'photos.url', 'photos.url_water', )
//            ->where('photos_client.user_id', $id)
//            ->get();
        $purchased_photos = DB::table('purchases')
            ->join('photos_client', 'purchases.photo_client_id', '=', 'photos_client.id')
            ->join('photos', 'photos_client.photo_id', '=', 'photos.id')
            ->join('albums', 'albums.id', '=', 'photos.album_id')
            ->join('events', 'events.id', '=', 'albums.event_id')
            ->select('photos_client.id', 'photos_client.photo_id', 'photos_client.user_id',  'events.name', 'events.date', 'photos.url', 'photos.url_water')
            ->where('photos_client.user_id', $id)
            ->get();

        foreach ($photos_client as $photo_client){
            $temp = Photo::where('id', $photo_client->photo_id)->first();
            $photo_client->url = Storage::disk('s3')->url($temp->url);
            $photo_client->url_water = Storage::disk('s3')->url($temp->url_water);

        }

        foreach ($purchased_photos as $purchased_photo){
            $purchased_photo->url = Storage::disk('s3')->url($purchased_photo->url);
            $purchased_photo->url_water = Storage::disk('s3')->url($purchased_photo->url_water);
        }

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'foto1_url' => $user->foto1_url,
            'foto2_url' => $user->foto2_url,
        ];

        return response()->json([
            'user' => $userData,
            'photos' => $photos_client,
            'purchased_photos' => $purchased_photos,
        ]);
    }

    public function purchasedPhotos(Request $request)
    {

        $token = Auth::user()->currentAccessToken();
        $user = $token->tokenable;
//        dd($request);

        $photoData = $request->json()->all();
//        dd($photoData['photos']);


        foreach ($photoData['photos'] as $photo) {
            $photoClientId = $photo['id'];

            $purchase = new Purchase();
            $purchase->photo_client_id = $photoClientId;
            $purchase->save();
        }
        return response()->json(['message' => 'Compras guardadas correctamente'], 200);
    }

}
