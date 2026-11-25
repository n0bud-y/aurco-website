<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SiteController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Home');
    }

    public function about(): Response
    {
        return Inertia::render('About');
    }

    public function services(): Response
    {
        return Inertia::render('Services');
    }

    public function credentials(): Response
    {
        return Inertia::render('Credentials');
    }

    public function projects(): Response
    {
        return Inertia::render('Projects');
    }

    public function gallery(): Response
    {
        return Inertia::render('Gallery');
    }

    public function contact(): Response
    {
        return Inertia::render('Contact');
    }

    /** Switches the site language and returns to the page the visitor was on. */
    public function locale(Request $request, string $locale): RedirectResponse
    {
        $request->session()->put('locale', $locale);

        $back = url()->previous();
        $sameSite = str_starts_with($back, url('/')) && ! str_contains($back, '/locale/');

        return redirect()->to($sameSite ? $back : url('/'));
    }

    public function storeMessage(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:60'],
            'subject' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:5000'],
            // Hidden field: real people leave it empty, bots fill it in.
            'website' => ['nullable', 'size:0'],
        ]);

        Message::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'] ?? null,
            'body' => $data['message'],
            'ip' => $request->ip(),
        ]);

        return back()->with('status', 'Message sent. Our team will reply within one business day.');
    }
}
