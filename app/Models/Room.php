<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'room_number',
        'status'
    ];

    public function type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
}
