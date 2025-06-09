<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    use HasFactory;

    // Hapus 'tags' dari $fillable
    protected $fillable = ['title', 'content', 'account_id', 'type'];

    // Hapus $casts untuk 'tags'
    // protected $casts = [
    //     'tags' => 'array',
    // ];

    public function author()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * Relasi Many-to-Many ke KnowledgeTag.
     */
    public function tags()
    {
        return $this->belongsToMany(KnowledgeTag::class, 'knowledge_base_knowledge_tag');
    }
}