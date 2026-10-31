<?php

namespace App\Http\Controllers;

use App\Services\Cms\PublishedCmsPageReader;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class PublicCmsPageController
{
    // String parameters keep implicit model binding from querying a missing CMS schema first.
    public function __invoke(Request $request, string $event, string $page, PublishedCmsPageReader $reader, ?string $publicLocale = null): Response
    {
        return Inertia::render('public/CmsPage', ['cms' => $reader->read($event, $page, app()->getLocale())])
            ->toResponse($request)
            ->withHeaders(['X-Robots-Tag' => 'noindex, nofollow', 'Cache-Control' => 'no-store, private']);
    }
}
