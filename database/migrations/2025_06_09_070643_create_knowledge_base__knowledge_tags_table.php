<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_base_knowledge_tag', function (Blueprint $table) {
            $table->foreignId('knowledge_base_id')->constrained()->onDelete('cascade');
            $table->foreignId('knowledge_tag_id')->constrained()->onDelete('cascade');
            $table->primary(['knowledge_base_id', 'knowledge_tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_base_knowledge_tag');
    }
};