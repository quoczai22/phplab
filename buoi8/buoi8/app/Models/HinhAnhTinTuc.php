<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HinhAnhTinTuc extends Model
{
    protected $table = 'hinh_anh_tin_tucs';
    protected $fillable = ['tin_id', 'duongdan', 'ghi_chu'];

    public function tin()
    {
        return $this->belongsTo(TinTuc::class, 'tin_id');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->duongdan);
    }
}
