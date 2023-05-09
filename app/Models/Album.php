<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = ['name' ,'event_id', 'user_id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
