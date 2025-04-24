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
    Schema::table('tickets', function (Blueprint $table) {
        // Gunakan DB::statement untuk PostgreSQL
        DB::statement('ALTER TABLE tickets ADD COLUMN "ID_Account" INTEGER NULL');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('tickets', function (Blueprint $table) {
        $table->dropColumn('ID_Account');
    });
}
};
