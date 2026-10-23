<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id', 'section_type', 'title', 'subtitle', 'content', 'image', 
        'button_text', 'button_link', 'settings', 'sort_order', 'status'
    ];

    protected $casts = [
        'settings' => 'array', // JSON column casting
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}