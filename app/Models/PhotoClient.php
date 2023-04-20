<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoClient extends Model
{
    use HasFactory;

    protected $table = 'photos_client';

    protected $fillable = [
        'photo_id',
        'user_id',
    ];

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
