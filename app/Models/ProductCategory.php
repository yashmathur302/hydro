<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'sort_order', 'status'
    ];

    // Parent Category ka relation
    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    // Sub-categories nikalne ke liye
    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    // Agar is category ke products nikalne ho to
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
