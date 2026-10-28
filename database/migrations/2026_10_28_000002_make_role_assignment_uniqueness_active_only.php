<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('role_assignments', function (Blueprint $table): void {
            // Keep the unique index: only revoked grants receive a repeatable NULL scope.
            $table->string('institution_scope', 36)->nullable()
                ->storedAs("case when revoked_at is null then coalesce(institution_id, '') else null end")
                ->change();
        });
    }

    public function down(): void
    {
        throw new LogicException('Reverting active-only uniqueness would conflict with preserved revocation history.');
    }
};
