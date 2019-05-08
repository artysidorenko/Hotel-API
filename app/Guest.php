<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    /**
     * Attributes that are fillable i.e. can be mass assigned via an array
     * 
     * @var array
     */
    protected $fillable = [
        'surname', 'name', 'age', 'room_id'
    ];
    
    /**
     * No need for created_at/updated_at
     */
    public $timestamps = false;

/**
     * Default value for room_id
     */
    protected $attributes = [
        'room_id' => null
    ];

    /**
     * Get the room for the guest.
     */
    public function room()
    {
        return $this->hasOne('App\Room');
    }

    public function booking()
    {
        return $this->hasOneThrough('App\Booking', 'App\Room');
    }
}
