<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Key/value store for the editable text blocks of the site. */
class Setting extends Model
{
    protected $fillable = ['group', 'payload'];

    protected $casts = ['payload' => 'array'];

    public static function group(string $group): array
    {
        return static::query()->where('group', $group)->value('payload') ?? [];
    }

    public static function putGroup(string $group, array $payload): void
    {
        static::query()->updateOrCreate(['group' => $group], ['payload' => $payload]);
    }
}
