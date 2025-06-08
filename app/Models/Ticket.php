<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'title', 'description', 'account_id', 'status_id', 'category_id', 'priority_id'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(TicketPriority::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }
}
