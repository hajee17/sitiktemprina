<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'user_id', 'location', 'category', 'description', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}