<?php

namespace App\Models;

use App\Models\Concerns\ContentItem;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use ContentItem;

    protected $fillable = ['question', 'answer', 'sort_order', 'is_published'];

    protected $casts = ['is_published' => 'boolean'];
}
