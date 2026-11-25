<?php

namespace App\Http\Middleware;

use App\Support\SiteContent;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    /**
     * Shared with every Inertia page. The closures keep the queries lazy, so
     * the Blade-rendered CRM never pays for them.
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'content' => fn () => SiteContent::payload(),
            'flash' => [
                'status' => fn () => $request->session()->get('status'),
            ],
        ]);
    }
}
