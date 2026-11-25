<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Support\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('admin.services.index', ['services' => Service::ordered()->get()]);
    }

    public function create(): View
    {
        return view('admin.services.form', ['service' => new Service(['is_published' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, imageRequired: true);
        $data['image_path'] = Media::store($request->file('image'), 'services');

        Service::create($data);

        return redirect()->route('admin.services.index')->with('status', 'Service created.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image_path'] = Media::store($request->file('image'), 'services', $service->image_path);

        $service->update($data);

        return redirect()->route('admin.services.index')->with('status', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        Media::delete($service->image_path);
        $service->delete();

        return back()->with('status', 'Service deleted.');
    }

    private function validated(Request $request, bool $imageRequired = false): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'summary' => ['nullable', 'string', 'max:2000'],
            'points' => ['nullable', 'string', 'max:8000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:6144'],
        ]);

        unset($data['image']);
        $data['points'] = collect(preg_split('/\r\n|\r|\n/', (string) ($data['points'] ?? '')))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
