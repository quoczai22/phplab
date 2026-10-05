<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    protected $table = 'danh_mucs';
    protected $fillable = ['ten', 'slug'];

    public function tins()
    {
        return $this->hasMany(TinTuc::class, 'danhmuc_id');
    }
}