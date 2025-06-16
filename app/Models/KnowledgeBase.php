<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class KnowledgeBase extends Model
{
    use HasFactory;

    // PERBAIKAN: Menambahkan 'file_path'
    protected $fillable = ['title', 'content', 'account_id', 'type', 'file_path', 'source_ticket_id'];

    public function author()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function tags()
    {
        return $this->belongsToMany(KnowledgeTag::class, 'knowledge_base_knowledge_tag');
    }

    public function sourceTicket()
    {
        return $this->belongsTo(Ticket::class, 'source_ticket_id');
    }

    protected function embedUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $url = $this->content;
                if ($this->type !== 'video' || !$url) {
                    return null;
                }

                if (str_contains($url, 'youtu.be') || str_contains($url, 'youtube.com/watch')) {
                     preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=))([^"&?/ ]{11})%i', $url, $match);
                     return 'https://www.youtube.com/embed/' . ($match[1] ?? '');
                }
                
                
                return null;
            },
        );
    }
}
