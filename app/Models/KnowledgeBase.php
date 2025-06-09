<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'account_id', 'tags'];

    /**
     * Otomatis cast kolom 'tags' dari JSON di DB menjadi array di PHP.
     */
    protected $casts = [
        'tags' => 'array',
    ];

    /**
     * Mendapatkan akun yang menulis artikel ini.
     */
    public function author()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}