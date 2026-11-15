<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'featured' => 'boolean',
        'is_flash_sale' => 'boolean',
        'flash_sale_end' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function effectivePrice()
    {
        if ($this->is_flash_sale && $this->flash_sale_price > 0) {
            return $this->flash_sale_price;
        }
        if ($this->sale_price > 0 && $this->sale_price < $this->regular_price) {
            return $this->sale_price;
        }
        return $this->regular_price;
    }

    public function discountPercent()
    {
        $eff = $this->effectivePrice();
        if ($this->regular_price > 0 && $eff < $this->regular_price) {
            return round((($this->regular_price - $eff) / $this->regular_price) * 100);
        }
        return 0;
    }
}

