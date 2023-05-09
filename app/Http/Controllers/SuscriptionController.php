<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SuscriptionController extends Controller
{
    public function index()
    {
        return 'Hola desde controlador de suscripciones';
    }

    public function create(Request $request, $event_id)
    {
        Subscription::create(
            [
                'event_id' => $event_id,
                'user_id' => auth()->id(),
            ]
        );
        return redirect()->route('events.index');
    }

    //Corregir
    public function delete(Request $request, $event_id)
    {
        Subscription::updated(
            [
                'event_id' => $event_id,
                'user_id' => auth()->id(),
            ]
        );
        return redirect()->route('events.index');
    }
}
