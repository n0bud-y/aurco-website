<?php

namespace App\Models;

use App\Models\Concerns\ContentItem;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use ContentItem;

    protected $fillable = ['name', 'role', 'quote', 'rating', 'avatar_path', 'sort_order', 'is_published'];

    protected $casts = ['is_published' => 'boolean', 'rating' => 'integer'];

    public function getAvatarUrlAttribute(): ?string
    {
        return Media::url($this->avatar_path);
    }
}
