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
            // For ticket_id foreign key
            DB::statement('
                ALTER TABLE ticket_comments 
                ADD CONSTRAINT ticket_comments_ticket_id_foreign 
                FOREIGN KEY (ticket_id) 
                REFERENCES tickets (id) 
                ON DELETE CASCADE
                DEFERRABLE INITIALLY IMMEDIATE
            ');

            // For account_id foreign key
            DB::statement('
                ALTER TABLE ticket_comments 
                ADD CONSTRAINT ticket_comments_account_id_foreign 
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
        Schema::table('ticket_comments', function (Blueprint $table) {
            //
        });
    }
};
