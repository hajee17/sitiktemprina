<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    use HasFactory;

    // PERBAIKAN: Menambahkan 'file_path'
    protected $fillable = ['title', 'content', 'account_id', 'type', 'file_path'];

    public function author()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function tags()
    {
        return $this->belongsToMany(KnowledgeTag::class, 'knowledge_base_knowledge_tag');
    }
}
