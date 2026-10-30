<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Source only: applying this migration requires separate owner GO.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_publication_events', function (Blueprint $table): void {
            $table->engine = 'InnoDB';
            $table->uuid('id')->primary();
            $table->foreignUuid('page_id')->constrained('cms_pages')->restrictOnDelete()->restrictOnUpdate();
            $table->foreignUuid('actor_user_id')->constrained('users')->restrictOnDelete()->restrictOnUpdate();
            $table->uuid('from_revision_id')->nullable();
            $table->uuid('to_revision_id');
            $table->enum('action', ['publish', 'restore']);
            $table->timestamp('occurred_at');
            $table->index(['page_id', 'occurred_at']);
            foreach (['from', 'to'] as $pointer) {
                $table->foreign(['page_id', $pointer.'_revision_id'], 'cms_publication_'.$pointer.'_fk')
                    ->references(['page_id', 'id'])->on('cms_page_revisions')->restrictOnDelete()->restrictOnUpdate();
            }
        });
    }

    public function down(): void
    {
        throw new LogicException('Forward-only: publication audit recovery requires owner review.');
    }
};
