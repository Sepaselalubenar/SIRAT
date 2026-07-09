<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPhoto extends Model
{
    protected $appends = [
        'url',
    ];

    protected $fillable = [
        'room_id',
        'path',
        'urutan',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * URL publik foto (asumsi disimpan di storage/app/public via disk 'public').
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
