<?php

namespace App\Models;

use App\Models\Concerns\ContentItem;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use ContentItem;

    protected $fillable = ['title', 'excerpt', 'body', 'link', 'image_path', 'sort_order', 'is_published', 'published_at'];

    protected $casts = ['is_published' => 'boolean', 'published_at' => 'date'];

    public function getImageUrlAttribute(): ?string
    {
        return Media::url($this->image_path);
    }
}
