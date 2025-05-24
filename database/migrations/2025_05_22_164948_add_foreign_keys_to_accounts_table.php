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
                ALTER TABLE accounts 
                ADD CONSTRAINT accounts_role_id_foreign 
                FOREIGN KEY (role_id) 
                REFERENCES roles (id) 
                ON DELETE CASCADE
                DEFERRABLE INITIALLY IMMEDIATE
            ');
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });
    }
};
