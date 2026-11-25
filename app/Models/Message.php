<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'subject', 'body', 'ip', 'read_at'];

    protected $casts = ['read_at' => 'datetime'];

    public function markRead(): void
    {
        if (! $this->read_at) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }
}
