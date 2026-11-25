<?php

namespace App\Http\Middleware;

use App\Support\Translator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Applies the visitor's chosen language (stored in the session) to the request. */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // ?lang=ar on any page switches language too, so the Arabic site can be shared as a link.
        if (array_key_exists((string) $request->query('lang'), Translator::LOCALES)) {
            $request->session()->put('locale', $request->query('lang'));
        }

        $locale = $request->session()->get('locale', config('app.locale', 'en'));

        app()->setLocale(array_key_exists($locale, Translator::LOCALES) ? $locale : 'en');

        return $next($request);
    }
}
