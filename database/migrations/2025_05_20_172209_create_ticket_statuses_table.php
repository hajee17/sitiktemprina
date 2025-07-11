<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // contoh: 'open', 'in_progress', 'closed'
            $table->timestamps();
        });
        DB::transaction(function () {
            DB::statement('ALTER TABLE ticket_statuses ADD CONSTRAINT ticket_statuses_name_unique UNIQUE (name)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_statuses');
    }
};
