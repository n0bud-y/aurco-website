<?php

namespace App\Http\Middleware;

use App\Support\SiteContent;
use App\Support\Translator;
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
        $locale = app()->getLocale();

        return array_merge(parent::share($request), [
            'locale' => $locale,
            'content' => fn () => Translator::translate(SiteContent::payload(), $locale),
            'flash' => [
                'status' => fn () => $request->session()->get('status'),
            ],
        ]);
    }
}
