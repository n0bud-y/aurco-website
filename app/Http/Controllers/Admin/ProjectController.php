<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Support\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', ['projects' => Project::ordered()->paginate(20)]);
    }

    public function create(): View
    {
        return view('admin.projects.form', ['project' => new Project(['is_published' => true, 'is_featured' => false])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, imageRequired: true);
        $data['image_path'] = Media::store($request->file('image'), 'projects');

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('status', 'Project created.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.form', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image_path'] = Media::store($request->file('image'), 'projects', $project->image_path);

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('status', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        Media::delete($project->image_path);
        $project->delete();

        return back()->with('status', 'Project deleted.');
    }

    private function validated(Request $request, bool $imageRequired = false): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'category' => ['nullable', 'string', 'max:60'],
            'location' => ['nullable', 'string', 'max:120'],
            'client' => ['nullable', 'string', 'max:160'],
            'year' => ['nullable', 'string', 'max:40'],
            'contract_value' => ['nullable', 'string', 'max:40'],
            'role' => ['nullable', 'string', 'max:160'],
            'summary' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:6144'],
        ]);

        unset($data['image']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
