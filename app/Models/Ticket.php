<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; 

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'account_id',
        'status_id',
        'category_id',
        'priority_id',
        'sbu_id',
        'department_id',
        'assignee_id',
        'clickup_task_id',
    ];

    public function author()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class, 'status_id');
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    public function priority()
    {
        return $this->belongsTo(TicketPriority::class, 'priority_id');
    }

    public function sbu()
    {
        return $this->belongsTo(Sbu::class, 'sbu_id');
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }
    
    public function assignee()
    {
        return $this->belongsTo(Account::class, 'assignee_id');
    }

    public function knowledgeBaseArticle()
    {
        return $this->hasOne(KnowledgeBase::class, 'source_ticket_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($ticket) {

            foreach ($ticket->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
            }

            $ticket->attachments()->delete();
        });
    }
}