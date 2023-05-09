<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
//    use HasFactory;

    protected $fillable = ['name', 'description', 'location', 'date', 'time', 'image', 'qr_code', 'user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function suscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

}
