<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'room_name',
        'starts_at',
        'ends_at',
        'note',
        'user_id'
    ];

    protected function casts():array {
            return [
                'starts_at' => 'datetime',
                'ends_at' => 'datetime'
            ];
    }

    public function user(): BelongsTo {

        return $this->belongsTo(User::class); 

    }
    
}
