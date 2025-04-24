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
    public function up()
{
    // Hubungkan tiket dengan akun yang sesuai
    // Contoh: hubungkan dengan akun pembuat pertama atau admin default
    DB::statement('
        UPDATE tickets 
        SET "ID_Account" = (
            SELECT "ID_Account" FROM account 
            WHERE "ID_Role" = 1 -- Role developer
            LIMIT 1
        )
        WHERE "ID_Account" IS NULL
    ');
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
    }
};
