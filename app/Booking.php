<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * Attributes that are fillable i.e. can be mass assigned via an array
     * 
     * @var array
     */
    protected $fillable = [
        'room_id', 'arrival', 'departure', 'guest_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'arrival',
        'departure'
    ];
    
    /**
     * No need for created_at/updated_at
     */
    public $timestamps = false;

    /**
     * Get the room associated with the booking.
     */
    public function room()
    {
        return $this->hasOne('App\Room');
    }

    /**
     * Get the guests for the booking.
     */
    public function guest()
    {
        return $this->hasMany('App\Guest');
    }

}
