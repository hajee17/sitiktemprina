<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeTag extends Model
{
    use HasFactory;
    protected $table = 'knowledge_tags';
    protected $fillable = ['name'];

    /**
     * Relasi Many-to-Many ke KnowledgeBase.
     */
    public function knowledgeBases()
    {
        return $this->belongsToMany(KnowledgeBase::class, 'knowledge_base_knowledge_tag');
    }

    
}