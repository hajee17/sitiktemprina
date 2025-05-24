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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
        });

        // 2. Tambahkan constraint foreign key secara terpisah
        Schema::table('tickets', function (Blueprint $table) {
            // Foreign key untuk account
            $table->foreign('account_id')
                  ->references('id')
                  ->on('accounts')
                  ->onDelete('cascade')
                  ->name('tickets_account_id_foreign');

            // Foreign key untuk status
            $table->foreign('status_id')
                  ->references('id')
                  ->on('ticket_statuses')
                  ->onDelete('restrict')
                  ->name('tickets_status_id_foreign');

            // Foreign key untuk category
            $table->foreign('category_id')
                  ->references('id')
                  ->on('ticket_categories')
                  ->onDelete('restrict')
                  ->name('tickets_category_id_foreign');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
