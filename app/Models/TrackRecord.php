<?php

namespace App\Models;

use App\Models\Concerns\ContentItem;
use Illuminate\Database\Eloquent\Model;

/** A row in one of the "track record" tables (completed construction, TCF, FM, rental, ongoing). */
class TrackRecord extends Model
{
    use ContentItem;

    /** Table key => heading shown on the Projects page and in the CRM. */
    public const SECTIONS = [
        'construction' => 'Completed construction projects',
        'tcf' => 'Completed TCF & modular projects',
        'fm' => 'Completed facility management projects',
        'rental' => 'Equipment rental framework agreements',
        'ongoing' => 'Ongoing projects',
    ];

    protected $fillable = [
        'section', 'title', 'client', 'location', 'scope', 'year',
        'contract_value', 'sort_order', 'is_published',
    ];

    protected $casts = ['is_published' => 'boolean'];
}
