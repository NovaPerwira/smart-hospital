<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsContent;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    private array $sections = ['nav', 'hero', 'services', 'why', 'about', 'cta', 'footer'];
    private array $locales = ['en' => 'English', 'id' => 'Indonesian'];

    public function index(Request $request)
    {
        $section = $request->get('section', 'hero');
        $locale = $request->get('locale', 'en');

        $contents = CmsContent::where('section', $section)
            ->where('locale', $locale)
            ->orderBy('key')
            ->get();

        return view('admin.cms.index', compact('contents', 'section', 'locale'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.key' => 'required|string',
            'updates.*.locale' => 'required|in:en,id',
            'updates.*.section' => 'nullable|string',
            'updates.*.value' => 'nullable|string',
        ]);

        $section = null;
        $locale = null;

        foreach ($request->updates as $item) {
            CmsContent::where('key', $item['key'])
                ->where('locale', $item['locale'])
                ->update(['value' => $item['value'] ?? '']);

            // Capture section/locale for the redirect
            if (!$section && isset($item['section'])) {
                $section = $item['section'];
            }
            if (!$locale) {
                $locale = $item['locale'];
            }
        }

        CmsContent::flushCache();

        return redirect()
            ->route('admin.cms.index', array_filter([
                'section' => $section ?? $request->get('section', 'hero'),
                'locale' => $locale ?? $request->get('locale', 'en'),
            ]))
            ->with('success', 'Content updated successfully.');
    }

    public function create(Request $request)
    {
        return view('admin.cms.create', [
            'sections' => $this->sections,
            'locales' => $this->locales,
            'section' => $request->get('section', 'hero'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|alpha_dash',
            'section' => 'required|string',
            'type' => 'required|in:text,textarea,html',
            'en' => 'required|string',
            'id' => 'required|string',
        ]);

        foreach (['en', 'id'] as $locale) {
            CmsContent::updateOrCreate(
                ['key' => $request->key, 'locale' => $locale],
                ['section' => $request->section, 'value' => $request->$locale, 'type' => $request->type]
            );
        }

        CmsContent::flushCache();

        return redirect()->route('admin.cms.index', ['section' => $request->section])
            ->with('success', "Content key '{$request->key}' saved.");
    }

    public function destroy(string $key)
    {
        CmsContent::where('key', $key)->delete();
        CmsContent::flushCache();

        return back()->with('success', "Key '{$key}' deleted.");
    }

    public function getSections(): array
    {
        return $this->sections;
    }

    public function getLocales(): array
    {
        return $this->locales;
    }
}
