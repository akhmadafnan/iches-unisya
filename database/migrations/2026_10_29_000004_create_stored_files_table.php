<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// DRAFT ICH-S0-05: execution requires separate owner GO APPLY MIGRATION.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stored_files', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained()->restrictOnDelete();
            $table->foreignUuid('uploaded_by_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('owner_user_id')->constrained('users')->restrictOnDelete();
            $table->enum('kind', ['abstract_pdf', 'payment_proof', 'cms_image']);
            $table->enum('disk', ['local']);
            $table->string('storage_key', 200)->unique();
            $table->string('original_name', 180);
            $table->string('detected_mime', 100);
            $table->unsignedBigInteger('size_bytes');
            $table->char('sha256', 64);
            $table->enum('visibility', ['private'])->default('private');
            $table->timestamps();
            $table->index(['event_id', 'owner_user_id', 'kind', 'created_at'], 'stored_files_owner_scope_index');
        });
    }

    public function down(): void
    {
        throw new LogicException('Forward-only: retained file metadata requires an owner-reviewed recovery plan.');
    }
};
