<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
//        dd($events);
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

//        $event = new Event();
//        $event->name = $request->input('name');
//        $event->description = $request->input('description');
//        $event->location = $request->input('location');
//        $event->date = $request->input('date');
//        $event->time = $request->input('time');
//        $event->user_id = auth()->user()->id;
//
//        try {
//            // Subir imagen de portada del evento
//            if ($request->hasFile('image')) {
//                $file = $request->file('image');
//                $extension = $file->getClientOriginalExtension();
//                $filename = uniqid() . '.' . $extension;
//                $filePath = 'events/' . $event->id . '/cover/' . $filename;
//                Storage::disk('s3')->put($filePath, file_get_contents($file));
//                $event->image = $filePath;
//            }
//
//            // Generar código QR
//            $qrCodeData = [
//                'name' => $event->name,
//                'description' => $event->description,
//                'location' => $event->location,
//                'date' => $event->date,
//                'time' => $event->time,
//            ];
//            $qrCodeString = json_encode($qrCodeData);
//            $qrCodePath = 'events/' . $event->id . '/qr/' . uniqid() . '.png';
//
//            Storage::disk('s3')->put($qrCodePath, QrCode::format('png')->size(300)->generate($qrCodeString));
//            $event->qr_code = $qrCodePath;
//
//            // Guardar evento en la base de datos
//            $event->save();
//
//            return redirect()->route('events.index')->with('success', 'El evento se ha creado correctamente.');
//
//        } catch (\Exception $e) {
////            dd($e->getMessage());
//            // Eliminar imagen de portada del evento en caso de error
//            if ($event->image) {
//                Storage::disk('s3')->delete($event->image);
//            }
//            // Eliminar carpeta del evento en caso de error
//            Storage::disk('s3')->deleteDirectory('events/' . $event->id);
//            return back()->with('error', 'Error al crear el evento: ' . $e->getMessage())->withInput();
//        }
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

//            // Genera el QR y guarda el archivo en el directorio correspondiente
//            $qrCode = QrCode::size(500)->generate('http://localhost:8000/events/' . $event->id);
//            $qrPath = 'events/'.$event->id.'/qr/' . $fileName;
//            Storage::disk('s3')->put($qrPath, $qrCode);
//
//            // Actualiza la información del evento con la ruta del QR
//            $event->qr_code = $qrPath;
//            $event->save();

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

}
