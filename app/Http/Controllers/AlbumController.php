<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Event;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    public function index(Request $request, $event_id)
    {
        if($event_id == null){
            $valor = 'No hay id';
        }else{
            $event = Event::where('id', $event_id)->first();
//            $nombreEvento = Event::where('id', $event_id)->pluck('name');
            $valor = 'El id es: '.$event_id;
        }

        $albums = Album::where('event_id', $event_id)->get();


//        dd($albums);
        return view('albums.index', compact('albums', 'event'));
    }

    public function show($event_id, $album_id){
        $album = Album::where('id', $album_id)->first();
//        dd($album, $event_id);
//        dd($album->id, $event_id ,$album->name);
        $photos = Photo::where('album_id', $album->id)->get();

        foreach ($photos as $photo){
            $photo->url = Storage::disk('s3')->url($photo->url);
        }

//        dd($photos);
        return view('albums.show', compact('album', 'event_id', 'photos'));
    }

    public function create(Request $request, $event_id)
    {
        $name = $request->input('name');
//        dd($name, $event_id);
        try {
//            Album::create(
//                [
//                    'name' => 'Album de prueba',
//                    'event_id' => $request->event_id,
//                    'user_id' => auth()->id(),
//                ]
//            );
            $album = new Album();
            $album->name = $name;
            $album->event_id = $event_id;
            $album->user_id = auth()->id();
//            dd($album);
            $album->save();
            return back();
        }catch (\Exception $e){
            return $e->getMessage();
        }

    }
}
