<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        return view('admin.posts.index', ['posts' => Post::ordered()->paginate(20)]);
    }

    public function create(): View
    {
        return view('admin.posts.form', ['post' => new Post(['is_published' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, imageRequired: true);
        $data['image_path'] = Media::store($request->file('image'), 'posts');

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('status', 'Post created.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image_path'] = Media::store($request->file('image'), 'posts', $post->image_path);

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('status', 'Post updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Media::delete($post->image_path);
        $post->delete();

        return back()->with('status', 'Post deleted.');
    }

    private function validated(Request $request, bool $imageRequired = false): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:20000'],
            'link' => ['nullable', 'url', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:6144'],
        ]);

        unset($data['image']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
