<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // 1. Tambahkan kolom baru dengan tipe BIGSERIAL
    DB::statement('ALTER TABLE account ADD COLUMN new_ID_Account BIGSERIAL');

    // 2. Salin data dari ID_Account ke new_ID_Account jika perlu
    DB::statement('UPDATE account SET new_ID_Account = ID_Account');

    // 3. Hapus kolom lama
    DB::statement('ALTER TABLE account DROP COLUMN IF EXISTS ID_Account');

    // 4. Ganti nama kolom baru menjadi ID_Account
    DB::statement('ALTER TABLE account RENAME COLUMN new_ID_Account TO ID_Account');

    // 5. Tambahkan kembali foreign key jika diperlukan
    DB::statement('ALTER TABLE chats ADD CONSTRAINT chats_id_account_foreign FOREIGN KEY (ID_Account) REFERENCES account(ID_Account) ON DELETE CASCADE');
    DB::statement('ALTER TABLE tickets ADD CONSTRAINT tickets_id_account_foreign FOREIGN KEY (ID_Account) REFERENCES account(ID_Account) ON DELETE CASCADE');
}

    public function down()
{
    // Rollback: Tambahkan kembali kolom lama dan hapus kolom baru jika perlu
    DB::statement('ALTER TABLE account ADD COLUMN ID_Account BIGINT');
    DB::statement('ALTER TABLE account DROP COLUMN IF EXISTS new_ID_Account');
}



};
