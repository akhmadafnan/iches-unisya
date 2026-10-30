<?php

// SYNTHETIC browser props through the real validator/presenter. No application boot or DB.
require __DIR__.'/../../../vendor/autoload.php';

use App\Services\Cms\PageSnapshotValidator;
use App\Services\Cms\PublishedSectionPresenter;
use Illuminate\Support\Str;
use Tests\Unit\PublishedSectionPresenterTest as Fixtures;

$snapshot = Fixtures::snapshot();
foreach (['en' => 'SYNTHETIC PUBLIC PAGE', 'id' => 'HALAMAN UJI SINTETIS', 'ar' => 'صفحة اختبار اصطناعية'] as $locale => $title) {
    $snapshot['locales'][$locale] = ['status' => 'ready', 'content' => ['title' => $title]];
    $snapshot['sections'][0]['locales'][$locale] = ['status' => 'ready', 'content' => ['body' => str_repeat($title.' — ', 3)]];
}
$snapshot['sections'][4]['bindings'] = array_map(fn ($field) => ['source' => 'event', 'field' => $field], ['name', 'timezone', 'starts_at', 'ends_at']);
$hidden = $snapshot['sections'][0];
$hidden['id'] = (string) Str::uuid7();
$hidden['position'] = count($snapshot['sections']);
$hidden['visible'] = false;
$hidden['locales']['en']['content']['body'] = 'HIDDEN SECRET';
$snapshot['sections'][] = $hidden;
$presenter = new PublishedSectionPresenter(new PageSnapshotValidator);
$event = Fixtures::event();
if (($argv[1] ?? '') === 'known') {
    $event->starts_at = '2027-01-05 02:15:00'; // Synthetic only, not an official conference hour.
}
$pages = [];
foreach (['en', 'id', 'ar'] as $locale) {
    $pages[$locale] = $presenter->present($presenter->editorial($snapshot), $event, 'synthetic-fixture', $locale);
}
echo json_encode($pages, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
