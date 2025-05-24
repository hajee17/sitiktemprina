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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->foreignId('role_id')->nullable();
            $table->jsonb('profile')->nullable();
            $table->timestamps();
        });

        Schema::table('accounts', function (Blueprint $table) {
            // Unique constraint untuk username
            $table->unique('username', 'accounts_username_unique');
            
            // Unique constraint untuk email
            $table->unique('email', 'accounts_email_unique');
            
            // Foreign key constraint (jika belum berhasil di atas)
            $table->foreign('role_id')
                  ->references('id')
                  ->on('roles')
                  ->onDelete('cascade')
                  ->name('accounts_role_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
