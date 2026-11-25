<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Support\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        return view('admin.testimonials.index', ['testimonials' => Testimonial::ordered()->get()]);
    }

    public function create(): View
    {
        return view('admin.testimonials.form', ['testimonial' => new Testimonial(['is_published' => true, 'rating' => 5])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['avatar_path'] = Media::store($request->file('avatar'), 'avatars');

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $this->validated($request);
        $data['avatar_path'] = Media::store($request->file('avatar'), 'avatars', $testimonial->avatar_path);

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        Media::delete($testimonial->avatar_path);
        $testimonial->delete();

        return back()->with('status', 'Testimonial deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['nullable', 'string', 'max:120'],
            'quote' => ['required', 'string', 'max:1200'],
            'rating' => ['required', 'integer', 'min:0', 'max:5'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'avatar' => ['nullable', 'image', 'max:4096'],
        ]);

        unset($data['avatar']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
