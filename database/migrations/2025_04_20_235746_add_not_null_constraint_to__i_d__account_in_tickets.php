<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE tickets ALTER COLUMN "ID_Account" SET NOT NULL');
        
        DB::statement('
            ALTER TABLE tickets 
            ADD CONSTRAINT tickets_id_account_foreign 
            FOREIGN KEY ("ID_Account") 
            REFERENCES account ("ID_Account")
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE tickets DROP CONSTRAINT tickets_id_account_foreign');
        DB::statement('ALTER TABLE tickets ALTER COLUMN "ID_Account" DROP NOT NULL');
    }
};
