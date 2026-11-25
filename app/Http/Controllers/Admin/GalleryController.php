<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Support\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('admin.gallery.index', ['images' => GalleryImage::ordered()->paginate(24)]);
    }

    public function create(): View
    {
        return view('admin.gallery.form', ['image' => new GalleryImage(['is_published' => true])]);
    }

    /** Accepts several files at once so a whole shoot can be uploaded in one go. */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'caption' => ['nullable', 'string', 'max:160'],
            'category' => ['nullable', 'string', 'max:60'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'max:6144'],
        ]);

        foreach ($request->file('images') as $index => $file) {
            GalleryImage::create([
                'caption' => $data['caption'] ?? null,
                'category' => $data['category'] ?? null,
                'sort_order' => ($data['sort_order'] ?? 0) + $index,
                'is_published' => $request->boolean('is_published'),
                'image_path' => Media::store($file, 'gallery'),
            ]);
        }

        return redirect()->route('admin.gallery.index')
            ->with('status', count($request->file('images')).' photo(s) added.');
    }

    public function edit(GalleryImage $image): View
    {
        return view('admin.gallery.form', compact('image'));
    }

    public function update(Request $request, GalleryImage $image): RedirectResponse
    {
        $data = $request->validate([
            'caption' => ['nullable', 'string', 'max:160'],
            'category' => ['nullable', 'string', 'max:60'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image' => ['nullable', 'image', 'max:6144'],
        ]);

        unset($data['image']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');
        $data['image_path'] = Media::store($request->file('image'), 'gallery', $image->image_path);

        $image->update($data);

        return redirect()->route('admin.gallery.index')->with('status', 'Photo updated.');
    }

    public function destroy(GalleryImage $image): RedirectResponse
    {
        Media::delete($image->image_path);
        $image->delete();

        return back()->with('status', 'Photo deleted.');
    }
}
