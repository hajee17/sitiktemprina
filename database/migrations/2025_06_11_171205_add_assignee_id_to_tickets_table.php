<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Method ini akan dijalankan saat Anda menjalankan 'php artisan migrate'
        Schema::table('tickets', function (Blueprint $table) {
            // Menambahkan kolom 'assignee_id'
            $table->unsignedBigInteger('assignee_id')
                  ->nullable() // Kolom ini boleh kosong (karena tiket mungkin belum ditugaskan)
                  ->after('department_id'); // (Opsional) Menempatkan kolom setelah department_id

            // Menambahkan foreign key constraint ke tabel 'accounts'
            $table->foreign('assignee_id')
                  ->references('id')
                  ->on('accounts')
                  ->onDelete('set null'); // Jika akun developer dihapus, assignee_id menjadi NULL
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Method ini akan dijalankan jika Anda melakukan 'migrate:rollback'
        Schema::table('tickets', function (Blueprint $table) {
            // Penting: Hapus foreign key terlebih dahulu sebelum menghapus kolomnya
            $table->dropForeign(['assignee_id']);
            $table->dropColumn('assignee_id');
        });
    }
};
