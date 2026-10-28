<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('password')->nullable();
        });

        Schema::create('auth_challenges', function (Blueprint $table): void {
            $table->id();
            $table->string('email', 254)->unique();
            $table->string('name');
            $table->char('code_hash', 64)->nullable();
            $table->char('token_hash', 64)->nullable()->unique();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('consumed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        throw new LogicException('Authentication history is preserved; no destructive rollback is provided.');
    }
};
