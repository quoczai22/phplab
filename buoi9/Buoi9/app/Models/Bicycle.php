<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bicycle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'bike_code',
        'station_id',
        'location_status',
        'condition_status',
        'rental_price_per_hour',
    ];

    public function station(){
        return $this->belongsTo(Station::class);
    }

    public function rentals(){
        return $this->hasMany(Rental::class);
    }

    public function reviews(){
        return $this->hasMany(BicycleReview::class);
    }
}
