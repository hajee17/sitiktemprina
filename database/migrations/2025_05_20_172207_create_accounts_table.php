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
            $table->foreignId('role_id');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        DB::transaction(function () {
            // Add unique constraints
            DB::statement('ALTER TABLE accounts ADD CONSTRAINT accounts_username_unique UNIQUE (username)');
            DB::statement('ALTER TABLE accounts ADD CONSTRAINT accounts_email_unique UNIQUE (email)');
            
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
