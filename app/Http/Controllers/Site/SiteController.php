<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use App\Support\PageMeta;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class SiteController extends Controller
{
    public function home(): Response
    {
        return $this->page('Home', 'home');
    }

    public function about(): Response
    {
        return $this->page('About', 'about');
    }

    public function services(): Response
    {
        return $this->page('Services', 'services');
    }

    public function credentials(): Response
    {
        return $this->page('Credentials', 'credentials');
    }

    public function projects(): Response
    {
        return $this->page('Projects', 'projects');
    }

    public function gallery(): Response
    {
        return $this->page('Gallery', 'gallery');
    }

    public function contact(): Response
    {
        return $this->page('Contact', 'contact');
    }

    /** Renders a page with its meta tags (title, description, share image) for search engines and link previews. */
    private function page(string $component, string $key): Response
    {
        return Inertia::render($component)->withViewData(['meta' => PageMeta::for($key)]);
    }

    /** sitemap.xml with the English and Arabic address of every page. */
    public function sitemap(): HttpResponse
    {
        $xml = view('sitemap', ['pages' => PageMeta::PAGES])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    public function robots(): HttpResponse
    {
        $body = PageMeta::indexable()
            ? "User-agent: *\nDisallow: /admin\nDisallow: /login\n\nSitemap: ".url('/sitemap.xml')."\n"
            : "# Staging copy: keep it out of search results.\nUser-agent: *\nDisallow: /\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
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
