<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email', 254);
            $table->string('normalized_email', 254)->storedAs('lower(trim(email))')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('institutions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('official_name');
            $table->string('display_name')->nullable();
            $table->char('country_code', 2)->nullable();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('slug', 100)->unique();
            $table->string('name');
            $table->unsignedSmallInteger('year');
            $table->string('timezone', 64)->default('UTC');
            // A date alone must never imply an official conference start hour.
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('status', 32)->default('draft');
            $table->timestamps();
        });

        Schema::create('role_assignments', function (Blueprint $table): void {
            // A conventional locator for this relationship; not a standalone domain UUID.
            $table->id();
            $table->foreignUuid('user_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('event_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('institution_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('role_key', 64);
            // MySQL allows repeated NULLs in unique keys: normalize event-wide scopes.
            $table->string('institution_scope', 36)->storedAs("coalesce(institution_id, '')");
            $table->foreignUuid('assigned_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'event_id', 'role_key', 'institution_scope'], 'role_assignments_scope_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_assignments');
        Schema::dropIfExists('events');
        Schema::dropIfExists('institutions');
        Schema::dropIfExists('users');
    }
};
