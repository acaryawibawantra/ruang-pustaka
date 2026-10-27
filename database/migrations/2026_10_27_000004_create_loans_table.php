<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('book_id')->constrained()->restrictOnDelete();
            $table->date('borrowed_at');
            $table->date('due_at');
            $table->date('returned_at')->nullable();
            $table->enum('status', ['borrowed', 'returned'])->default('borrowed');
            $table->timestamps();

            $table->index(['status', 'due_at']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE `loans` ADD CONSTRAINT `loans_return_date_matches_status_check` CHECK ((`status` = 'borrowed' AND `returned_at` IS NULL) OR (`status` = 'returned' AND `returned_at` IS NOT NULL))",
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
