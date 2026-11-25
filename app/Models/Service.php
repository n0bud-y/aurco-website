<?php

namespace App\Models;

use App\Models\Concerns\ContentItem;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use ContentItem;

    protected $fillable = ['title', 'summary', 'points', 'image_path', 'sort_order', 'is_published'];

    protected $casts = ['is_published' => 'boolean', 'points' => 'array'];

    public function getImageUrlAttribute(): ?string
    {
        return Media::url($this->image_path);
    }
}
