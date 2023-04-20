<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DiskController extends Controller
{
    public function index()
    {
        Storage::put('test.txt', 'Hello World');

        $content = Storage::get('test.txt');
        var_dump($content);
    }
}
