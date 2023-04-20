<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'url_water','album_id'];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function photoClients()
    {
        return $this->belongsToMany(User::class, 'photos_client', 'photo_id', 'user_id');
    }
}
