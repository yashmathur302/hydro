<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    // created_at hai par updated_at nahi hai table me, isliye isko false karna hoga
    public $timestamps = false; 

    protected $fillable = ['product_id', 'image', 'alt_text', 'sort_order', 'created_at'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}