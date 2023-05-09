<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventController extends Controller
{
    public function index()
    {
        $id_user = auth()->id();
        if(Subscription::where('user_id', $id_user)->exists()){ //Busca si el usuario tiene suscripciones
            $suscriptions = Subscription::where('user_id', $id_user)->get(); //Guarda las suscripciones del usuario
//            dd($suscriptions);
            $temporal = [];
            foreach ($suscriptions as $suscription) {
                $suscription = Event::where('id', $suscription->event_id)->first(); //Guarda los eventos de las suscripciones del usuario
                $temporal[] = $suscription;

            }
        }
        else{
//            $suscriptions = null;
//            return 'No tienes subs';

            $events = Event::all();
            foreach ($events as $event) {
                $event->image = Storage::disk('s3')->url($event->image);
                $event->subscription = false;
            }
            return view('events.index', compact('events'));
        }


//        return $suscriptions;
//        return $temporal;
        $events = Event::all();

        foreach ($events as $event) {
            $event->image = Storage::disk('s3')->url($event->image);
        }

        $eventIds = collect($temporal)->pluck('id');

        foreach ($events as $event) {
            if ($eventIds->contains($event->id)) {
//                $event->status = "active";
                $event->subscription = true;
            } else {
//                $event->status = "cancelled";
                $event->subscription = false;
            }
        }


        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'location' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'image' => 'required|image',
        ]);

        try {
            // Crea el registro del evento en la base de datos
            $event = new Event;
            $event->name = $request->input('name');
            $event->description = $request->input('description');
            $event->location = $request->input('location');
            $event->date = $request->input('date');
            $event->time = $request->input('time');
            $event->user_id = auth()->id();
            $event->save();

            // Genera la imagen del evento y guarda el archivo en el directorio correspondiente
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = 'events/'.$event->id.'/cover/'.$fileName;
                Storage::disk('s3')->put($imagePath, file_get_contents($image));

                // Actualiza la información del evento con la ruta de la imagen
                $event->image = $imagePath;
            }

            //Generar código QR
            $qrCodeData = [
                'name' => $event->name,
                'description' => $event->description,
                'location' => $event->location,
                'date' => $event->date,
                'time' => $event->time,
            ];
            $qrCodeString = json_encode($qrCodeData);
            $qrCodePath = 'events/' . $event->id . '/qr/' . uniqid() . '.png';

            Storage::disk('s3')->put($qrCodePath, QrCode::format('png')->size(300)->generate($qrCodeString));
            $event->qr_code = $qrCodePath;

            // Guardar evento en la base de datos
            $event->save();

            return redirect()->route('events.index')->with('success', 'El evento se ha creado correctamente.');
        } catch (\Exception $e) {
//            dd($e->getMessage());
            // Elimina el registro del evento si la creación falla
            if ($event && $event->id) {
                $event->delete();
            }

            // Borra las imágenes del evento si la subida falla
            if (isset($imagePath) && Storage::disk('s3')->exists($imagePath)) {
                Storage::disk('s3')->delete($imagePath);
            }
            if (isset($qrPath) && Storage::disk('s3')->exists($qrPath)) {
                Storage::disk('s3')->delete($qrPath);
            }

            // Regresa a la vista del formulario con un mensaje de error
            return back()->with('error', 'Hubo un problema al crear el evento: '.$e->getMessage());
        }


    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        $event->qr_code = Storage::disk('s3')->url($event->qr_code);
//        dd($event);
        return view('events.show', compact('event'));
    }

}
