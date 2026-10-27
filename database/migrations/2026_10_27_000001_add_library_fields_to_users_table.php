<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nrp_nim', 32)->nullable()->unique();
            $table->string('role', 20)->default('member');
            $table->boolean('is_active')->default(true);
        });

        DB::table('users')
            ->whereNull('nrp_nim')
            ->select('id')
            ->orderBy('id')
            ->eachById(function (object $user): void {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['nrp_nim' => 'LEGACY-'.$user->id]);
            });

        Schema::table('users', function (Blueprint $table) {
            $table->string('nrp_nim', 32)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nrp_nim']);
            $table->dropColumn(['nrp_nim', 'role', 'is_active']);
        });
    }
};
