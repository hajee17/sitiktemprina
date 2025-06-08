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
                ALTER TABLE tickets 
                ADD CONSTRAINT tickets_account_id_foreign 
                FOREIGN KEY (account_id) 
                REFERENCES accounts(id) 
                ON DELETE CASCADE
                DEFERRABLE INITIALLY IMMEDIATE
            ');

            DB::statement('
                ALTER TABLE tickets 
                ADD CONSTRAINT tickets_status_id_foreign 
                FOREIGN KEY (status_id) 
                REFERENCES ticket_statuses(id) 
                ON DELETE RESTRICT
                DEFERRABLE INITIALLY IMMEDIATE
            ');

            DB::statement('
                ALTER TABLE tickets 
                ADD CONSTRAINT tickets_category_id_foreign 
                FOREIGN KEY (category_id) 
                REFERENCES ticket_categories(id) 
                ON DELETE RESTRICT
                DEFERRABLE INITIALLY IMMEDIATE
            ');

            DB::statement('
                ALTER TABLE tickets 
                ADD CONSTRAINT tickets_priority_id_foreign 
                FOREIGN KEY (priority_id) 
                REFERENCES ticket_priorities(id) 
                ON DELETE RESTRICT
                DEFERRABLE INITIALLY IMMEDIATE
            ');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            //
        });
    }
};
