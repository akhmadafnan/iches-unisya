<?php

namespace App\Http\Controllers;

use App\Services\Cms\PublicSeo;
use App\Services\Cms\PublishedCmsPageReader;

class PublicSitemapController
{
    public function __invoke(PublicSeo $seo, PublishedCmsPageReader $reader)
    {
        $origin = $seo->origin();
        $event = config('iches-public.event_slug');
        $urls = [];
        if ($origin && is_string($event) && preg_match('/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', $event)) {
            foreach ($reader->sitemap($event) as $page) {
                foreach ($page['locales'] as $locale) {
                    $urls[] = $origin.$seo->path($event, $page['slug'], $locale);
                }
            }
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach (array_unique($urls) as $url) {
            $xml .= '<url><loc>'.htmlspecialchars($url, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</loc></url>';
        }

        return response($xml.'</urlset>')->withHeaders([
            'Content-Type' => 'application/xml; charset=UTF-8', 'X-Robots-Tag' => 'noindex, nofollow',
            'Cache-Control' => 'private, no-store',
        ]);
    }
}
