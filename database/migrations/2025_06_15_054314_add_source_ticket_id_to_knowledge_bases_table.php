<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('knowledge_bases', function (Blueprint $table) {
            // Kolom untuk menautkan ke tiket asal. Boleh null.
            $table->foreignId('source_ticket_id')->nullable()->constrained('tickets')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('knowledge_bases', function (Blueprint $table) {
            $table->dropForeign(['source_ticket_id']);
            $table->dropColumn('source_ticket_id');
        });
    }
};
