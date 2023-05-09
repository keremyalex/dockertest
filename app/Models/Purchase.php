<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo_client_id',
        'purchase_date',
    ];

    public function photoClient()
    {
        return $this->belongsTo(PhotoClient::class);
    }
}
