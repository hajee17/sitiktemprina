<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    protected $fillable = ['title', 'content', 'account_id', 'tags'];

    protected $casts = [
        'tags' => 'array',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

