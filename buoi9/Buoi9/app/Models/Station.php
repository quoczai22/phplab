<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'station_name',
        'address',
        'ward',
        'total_docks',
        'status',
    ];

    public function bicycles(){
        return $this->hasMany(Bicycle::class);
    }

    public function rentalsFrom(){
        return $this->hasMany(Rental::class,'rent_station_id');
    }

    public function rentalsTo()
    {
        return $this->hasMany(Rental::class,'return_station_id');
    }

    public function reviews(){
        return $this->hasMany(StationReview::class);
    }

    public function stationReviews(){
        return $this->hasMany(StationReview::class);
    }
}
