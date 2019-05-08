<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{

    /**
     * Attributes that are fillable i.e. can be mass assigned via an array
     * 
     * @var array
     */
    protected $fillable = [
        'beds', 'floor', 'available', 'price'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'available' => 'boolean',
    ];

    /**
     * No need for created_at/updated_at
     */
    public $timestamps = false;

    /**
     * Get the booking that holds the room.
     */
    public function booking()
    {
        return $this->belongsTo('App\Booking');
    }
}
