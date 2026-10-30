<?php

// DB-free diagnostic only. Does not execute any tests/Database file or persist models.
require __DIR__.'/../../../../vendor/autoload.php';

use App\Models\CmsPageRevision;
use App\Services\Cms\PageRevisionWriter;
use App\Services\Cms\PageSnapshotValidator;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Validation\ValidationException;
use Tests\Unit\PublishedSectionPresenterTest as Fixtures;

foreach (['ICHES_DB_TESTS', 'ICHES_CMS_DB_TESTS', 'ICHES_CMS_LIFECYCLE_DB_TESTS', 'ICHES_CMS_UI_DB_TESTS', 'ICHES_CMS_PUBLIC_RENDER_DB_TESTS'] as $flag) {
    if (getenv($flag) !== '0') {
        throw new RuntimeException('All boolean DB opt-ins must explicitly be 0.');
    }
}
if (getenv('ICHES_CMS_DB_SCHEMA') !== false && getenv('ICHES_CMS_DB_SCHEMA') !== '') {
    throw new RuntimeException('Schema opt-in must be unset/empty.');
}
$app = require __DIR__.'/../../../../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
config(['database.default' => 'disabled']);
$validator = new PageSnapshotValidator;
$writer = new PageRevisionWriter($validator);
$event = Fixtures::event();
$report = ['kind' => 'DB-FREE DIAGNOSIS; NO MYSQL OR PERSISTENCE TEST EXECUTION', 'checks' => []];
$check = function (bool $condition, string $label) use (&$report): void {
    if (! $condition) {
        throw new RuntimeException($label);
    }
    $report['checks'][] = $label;
};
$hydrate = function (array $snapshot): array {
    $model = new CmsPageRevision;
    $model->snapshot = $snapshot;
    $raw = $model->getAttributes();

    return (new CmsPageRevision)->newFromBuilder($raw)->snapshot;
};
foreach (['Published persisted title', 'First publication', 'Second publication', 'PRIVATE unpublished title'] as $title) {
    $snapshot = Fixtures::snapshot();
    $snapshot['locales']['en']['content']['title'] = $title;
    foreach ($snapshot['sections'] as &$section) {
        $section['media_ids'] = [];
    }
    $check($validator->validate($snapshot) === $snapshot, 'Original fixture preparation validates: '.$title);
    $check($writer->validateSnapshot($event, $snapshot) === $snapshot, 'Pre-persistence writer validation passes: '.$title);
    $check($validator->validate($hydrate($snapshot)) === $snapshot, 'Ordinary Eloquent JSON cast/hydration round-trip passes: '.$title);
    $check(count($snapshot['sections']) === 7, 'All seven sections retained: '.$title);
}

// Change object-member order only; values, array order, types and safety coverage stay identical.
$original = $snapshot;
$countdown = array_search('countdown', array_column($snapshot['sections'], 'component_key'), true);
$snapshot['sections'][$countdown]['bindings'] = [['field' => 'starts_at', 'source' => 'event']];
$check($original == $snapshot, 'Reordered binding has identical associative key/value semantics');
$check($original !== $snapshot, 'Only associative member order differs under PHP strict identity');
$hydrated = $hydrate($snapshot);
$check(array_keys($hydrated['sections'][$countdown]['bindings'][0]) === ['field', 'source'], 'Eloquent hydration preserves reordered object keys');
foreach (['validator' => fn () => $validator->validate($hydrated), 'writer revalidation' => fn () => $writer->validateSnapshot($event, $hydrated)] as $boundary => $probe) {
    try {
        $probe();
        throw new RuntimeException('Expected exact reported validation exception at '.$boundary);
    } catch (ValidationException $exception) {
        $check($exception->getMessage() === 'Invalid or unsafe CMS snapshot contract (schema version 1).', 'Exact reported exception reproduced at '.$boundary);
        $report['rejection'][$boundary] = array_values(array_map(
            fn ($frame) => ['function' => $frame['function'], 'line' => $frame['line']],
            array_filter($exception->getTrace(), fn ($frame) => isset($frame['file']) && str_ends_with(str_replace('\\', '/', $frame['file']), '/app/Services/Cms/PageSnapshotValidator.php')),
        ));
    }
}

// The same reordering outside countdown passes: isolate the order-sensitive production comparison.
$control = $snapshot;
$control['sections'][$countdown]['component_key'] = 'event-essentials';
$check($validator->validate($control) === $control, 'Same reordered allowed binding passes for event-essentials');
$report['limits'] = ['Object-member reordering was simulated, not observed from MySQL in this authorization.',
    'DB-free reproduction proves the production validator is order-sensitive; actual prior MySQL failure stage needs its full stack trace or separately authorized verification.',
    'No validator, writer, lifecycle, reader, model, fixture test or security contract was changed.'];
echo json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL;
