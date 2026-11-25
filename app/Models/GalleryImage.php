<?php

namespace App\Models;

use App\Models\Concerns\ContentItem;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use ContentItem;

    protected $fillable = ['caption', 'category', 'image_path', 'sort_order', 'is_published'];

    protected $casts = ['is_published' => 'boolean'];

    public function getImageUrlAttribute(): ?string
    {
        return Media::url($this->image_path);
    }
}
