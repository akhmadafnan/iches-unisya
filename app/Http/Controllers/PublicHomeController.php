<?php

namespace App\Http\Controllers;

use App\Services\Cms\PublishedCmsPageReader;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PublicHomeController
{
    public function __invoke(Request $request, PublishedCmsPageReader $reader, ?string $publicLocale = null): Response
    {
        $cms = null;
        $event = config('iches-public.event_slug');
        $page = config('iches-public.homepage_slug');

        // Missing or invalid canonical configuration must not trigger database discovery.
        if (is_string($event) && preg_match('/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', $event)
            && is_string($page) && preg_match('/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', $page)) {
            try {
                $cms = $reader->read($event, $page, app()->getLocale());
            } catch (ModelNotFoundException) {
                // The configured event, page or published revision does not exist yet.
            } catch (HttpException $exception) {
                if ($exception->getStatusCode() !== 404) {
                    throw $exception;
                }
            }
        }

        return Inertia::render($cms === null ? 'public/Home' : 'public/CmsPage', $cms === null ? [] : ['cms' => $cms])
            ->toResponse($request)
            ->withHeaders(['X-Robots-Tag' => 'noindex, nofollow', 'Cache-Control' => 'no-store, private']);
    }
}
