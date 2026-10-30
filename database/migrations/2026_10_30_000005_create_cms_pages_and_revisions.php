<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Source only: applying this migration requires separate owner GO.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_pages', function (Blueprint $table): void {
            $table->engine = 'InnoDB';
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained()->restrictOnDelete();
            $table->string('slug', 120);
            $table->uuid('draft_revision_id')->nullable();
            $table->uuid('published_revision_id')->nullable();
            $table->timestamps();
            $table->unique(['event_id', 'slug']);
        });

        Schema::create('cms_page_revisions', function (Blueprint $table): void {
            $table->engine = 'InnoDB';
            $table->uuid('id')->primary();
            $table->foreignUuid('page_id')->constrained('cms_pages')->restrictOnDelete();
            $table->unsignedInteger('version');
            $table->foreignUuid('created_by_user_id')->constrained('users')->restrictOnDelete();
            $table->unsignedSmallInteger('schema_version');
            $table->json('snapshot');
            $table->timestamp('created_at');
            $table->unique(['page_id', 'version']);
            $table->unique(['page_id', 'id'], 'cms_revision_page_identity');
        });

        Schema::table('cms_pages', function (Blueprint $table): void {
            // Composite references prevent pointing to another page's revision, including across events.
            $table->foreign(['id', 'draft_revision_id'], 'cms_page_draft_fk')
                ->references(['page_id', 'id'])->on('cms_page_revisions')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign(['id', 'published_revision_id'], 'cms_page_published_fk')
                ->references(['page_id', 'id'])->on('cms_page_revisions')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        throw new LogicException('Forward-only: CMS history requires an owner-reviewed recovery plan.');
    }
};
