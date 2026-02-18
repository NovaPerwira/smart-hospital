<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CmsContent extends Model
{
    protected $fillable = ['key', 'locale', 'section', 'value', 'type'];

    /**
     * Get all content for a locale, keyed by content key.
     * Cached for 10 minutes.
     */
    public static function forLocale(string $locale): array
    {
        return Cache::remember("cms_{$locale}", 600, function () use ($locale) {
            return static::where('locale', $locale)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Get a single value by key and locale, with fallback.
     */
    public static function get(string $key, string $locale = 'en', string $fallback = ''): string
    {
        $content = static::forLocale($locale);
        return $content[$key] ?? (static::forLocale('en')[$key] ?? $fallback);
    }

    /**
     * Flush CMS cache for all locales.
     */
    public static function flushCache(): void
    {
        Cache::forget('cms_en');
        Cache::forget('cms_id');
    }
}
