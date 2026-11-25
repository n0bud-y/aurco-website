<?php

namespace App\Models;

use App\Models\Concerns\ContentItem;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use ContentItem;

    protected $fillable = [
        'title', 'category', 'location', 'client', 'year', 'contract_value', 'role', 'summary', 'image_path',
        'sort_order', 'is_featured', 'is_published',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function getImageUrlAttribute(): ?string
    {
        return Media::url($this->image_path);
    }
}
