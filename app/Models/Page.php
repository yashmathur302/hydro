<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'page_type', 'content', 'meta_title', 'meta_description', 'og_image'
    ];

    // Ek page ke multiple sections ho sakte hain
    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order', 'asc');
    }
}
