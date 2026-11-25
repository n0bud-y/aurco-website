<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use App\Support\SiteContent;
use App\Support\Translator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

/** Arabic text for everything the website shows from the CRM. */
class TranslationController extends Controller
{
    private const LOCALE = 'ar';

    private const PER_PAGE = 40;

    public function index(Request $request): View
    {
        $map = Translation::map(self::LOCALE);
        $filter = $request->query('show', 'all');
        $search = trim((string) $request->query('q', ''));

        $rows = collect(Translator::strings(SiteContent::payload()))
            ->map(fn (string $source) => [
                'hash' => Translation::hash($source),
                'source' => $source,
                'text' => $map[Translation::hash($source)] ?? '',
            ]);

        $total = $rows->count();
        $done = $rows->filter(fn ($row) => $row['text'] !== '')->count();

        $rows = $rows
            ->when($filter === 'missing', fn ($c) => $c->filter(fn ($row) => $row['text'] === ''))
            ->when($search !== '', fn ($c) => $c->filter(fn ($row) => str_contains(mb_strtolower($row['source'].' '.$row['text']), mb_strtolower($search))))
            ->values();

        $page = LengthAwarePaginator::resolveCurrentPage();
        $paginator = new LengthAwarePaginator(
            $rows->forPage($page, self::PER_PAGE)->values(),
            $rows->count(),
            self::PER_PAGE,
            $page,
            ['path' => $request->url(), 'query' => $request->query()],
        );

        return view('admin.translations.index', [
            'rows' => $paginator,
            'total' => $total,
            'done' => $done,
            'filter' => $filter,
            'search' => $search,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'items' => ['array'],
            'items.*.source' => ['required', 'string', 'max:10000'],
            'items.*.text' => ['nullable', 'string', 'max:10000'],
        ]);

        foreach ($data['items'] ?? [] as $item) {
            Translation::put(self::LOCALE, $item['source'], $item['text'] ?? null);
        }

        return back()->with('status', 'Arabic text saved.');
    }
}
