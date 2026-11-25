<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function edit(?string $group = null): View
    {
        $groups = config('content.groups');
        $group = $group ?: array_key_first($groups);

        abort_unless(isset($groups[$group]), 404);

        return view('admin.content.edit', [
            'groups' => $groups,
            'group' => $group,
            'definition' => $groups[$group],
            'values' => Setting::group($group),
        ]);
    }

    public function update(Request $request, string $group): RedirectResponse
    {
        $definition = config("content.groups.$group");

        abort_unless($definition, 404);

        $rules = [];
        foreach ($definition['fields'] as $key => $field) {
            $rules["fields.$key"] = $field['type'] === 'image'
                ? 'nullable|image|max:6144'
                : ($field['rules'] ?? 'nullable|string|max:5000');
        }
        $request->validate($rules);

        $values = Setting::group($group);

        foreach ($definition['fields'] as $key => $field) {
            if ($field['type'] === 'image') {
                if ($request->boolean("remove.$key")) {
                    Media::delete($values[$key] ?? null);
                    $values[$key] = null;
                } else {
                    $values[$key] = Media::store($request->file("fields.$key"), 'content', $values[$key] ?? null);
                }

                continue;
            }

            $input = $request->input("fields.$key");

            $values[$key] = match ($field['type']) {
                'lines' => $this->toLines($input),
                'pairs' => $this->toPairs($input),
                default => $input,
            };
        }

        Setting::putGroup($group, $values);

        return redirect()->route('admin.content.edit', $group)->with('status', 'Content saved.');
    }

    private function toLines(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function toPairs(?string $value): array
    {
        return collect($this->toLines($value))
            ->map(function (string $line) {
                [$label, $href] = array_pad(explode('|', $line, 2), 2, '#');

                return ['label' => trim($label), 'href' => trim($href) ?: '#'];
            })
            ->all();
    }
}
