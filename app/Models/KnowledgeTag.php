<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeTag extends Model
{
    use HasFactory;
    protected $table = 'knowledge_tags';
    protected $fillable = ['name'];
}