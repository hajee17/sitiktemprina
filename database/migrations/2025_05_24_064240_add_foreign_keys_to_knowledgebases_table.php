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
        DB::transaction(function () {
            DB::statement('
                ALTER TABLE knowledge_bases 
                ADD CONSTRAINT knowledge_bases_account_id_foreign 
                FOREIGN KEY (account_id) 
                REFERENCES accounts (id) 
                ON DELETE CASCADE
                DEFERRABLE INITIALLY IMMEDIATE
        ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knowledgebases', function (Blueprint $table) {
            //
        });
    }
};
