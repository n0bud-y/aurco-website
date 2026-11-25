<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrackRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TrackRecordController extends Controller
{
    public function index(Request $request): View
    {
        $section = $request->query('section');
        $section = array_key_exists((string) $section, TrackRecord::SECTIONS) ? $section : array_key_first(TrackRecord::SECTIONS);

        return view('admin.track-records.index', [
            'sections' => TrackRecord::SECTIONS,
            'section' => $section,
            'records' => TrackRecord::query()->where('section', $section)->ordered()->get(),
            'counts' => TrackRecord::query()->selectRaw('section, count(*) as total')->groupBy('section')->pluck('total', 'section'),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.track-records.form', [
            'record' => new TrackRecord(['is_published' => true, 'section' => $request->query('section', 'construction')]),
            'sections' => TrackRecord::SECTIONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $record = TrackRecord::create($this->validated($request));

        return redirect()->route('admin.track-records.index', ['section' => $record->section])->with('status', 'Record added.');
    }

    public function edit(TrackRecord $trackRecord): View
    {
        return view('admin.track-records.form', ['record' => $trackRecord, 'sections' => TrackRecord::SECTIONS]);
    }

    public function update(Request $request, TrackRecord $trackRecord): RedirectResponse
    {
        $trackRecord->update($this->validated($request));

        return redirect()->route('admin.track-records.index', ['section' => $trackRecord->section])->with('status', 'Record updated.');
    }

    public function destroy(TrackRecord $trackRecord): RedirectResponse
    {
        $trackRecord->delete();

        return back()->with('status', 'Record deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'section' => ['required', Rule::in(array_keys(TrackRecord::SECTIONS))],
            'title' => ['required', 'string', 'max:255'],
            'client' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'scope' => ['nullable', 'string', 'max:2000'],
            'year' => ['nullable', 'string', 'max:40'],
            'contract_value' => ['nullable', 'string', 'max:40'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
