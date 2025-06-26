<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\KnowledgeBase;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class KnowledgeBaseRecommenderService
{
    /**
     * Dapatkan rekomendasi artikel Knowledge Base untuk sebuah tiket.
     *
     * @param Ticket $ticket Tiket yang menjadi target.
     * @param int $limit Jumlah rekomendasi yang diinginkan.
     * @return Collection Koleksi model KnowledgeBase yang sudah diurutkan.
     */
    public function getRecommendations(Ticket $ticket, int $limit = 3): Collection
    {

        $knowledgeBases = KnowledgeBase::where('type', 'blog')->get();

        if ($knowledgeBases->isEmpty()) {
            return collect();
        }

        $ticketText = $ticket->title . ' ' . $ticket->description;

        $documents = $knowledgeBases->mapWithKeys(function ($kb) {
            return [$kb->id => $this->preprocess($kb->title . ' ' . $kb->content)];
        });
        $ticketVector = $this->preprocess($ticketText);

        $corpus = $documents->prepend($ticketVector, 'ticket_target');
        $idf = $this->calculateIdf($corpus->all());

        $ticketTfIdf = $this->calculateTfIdf($this->calculateTf($ticketVector), $idf);
        $documentTfIdfs = $documents->map(function ($doc) use ($idf) {
            return $this->calculateTfIdf($this->calculateTf($doc), $idf);
        });

        $scores = $documentTfIdfs->map(function ($docTfIdf, $kbId) use ($ticketTfIdf) {
            return [
                'kb_id' => $kbId,
                'score' => $this->cosineSimilarity($ticketTfIdf, $docTfIdf),
            ];
        });

        $sortedScores = $scores->sortByDesc('score')->values()->all();

        $recommendedIds = collect($sortedScores)->pluck('kb_id')->take($limit);

        return $knowledgeBases->whereIn('id', $recommendedIds)
                              ->sortBy(function($kb) use ($recommendedIds) {
                                  return array_search($kb->id, $recommendedIds->all());
                              });
    }

    /**
     * Membersihkan dan memecah teks menjadi token/kata.
     */
    private function preprocess(string $text): array
    {
        $text = Str::lower($text); 
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text); 
        return preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Menghitung Term Frequency (TF) - Seberapa sering sebuah kata muncul.
     */
    private function calculateTf(array $terms): array
    {
        return array_count_values($terms);
    }

    /**
     * Menghitung Inverse Document Frequency (IDF) - Seberapa unik sebuah kata di seluruh dokumen.
     */
    private function calculateIdf(array $documents): array
    {
        $docCount = count($documents);
        $termDocCount = [];
        $idf = [];

        foreach ($documents as $doc) {
            foreach (array_unique($doc) as $term) {
                $termDocCount[$term] = ($termDocCount[$term] ?? 0) + 1;
            }
        }
        
        foreach ($termDocCount as $term => $count) {
            $idf[$term] = log($docCount / $count);
        }

        return $idf;
    }
    
    /**
     * Menghitung skor TF-IDF untuk setiap kata dalam dokumen.
     */
    private function calculateTfIdf(array $tf, array $idf): array
    {
        $tfidf = [];
        foreach ($tf as $term => $frequency) {
            $tfidf[$term] = $frequency * ($idf[$term] ?? 0);
        }
        return $tfidf;
    }

    /**
     * Menghitung Cosine Similarity antara dua vektor dokumen.
     */
    private function cosineSimilarity(array $vecA, array $vecB): float
    {
        $dotProduct = 0;
        $magA = 0;
        $magB = 0;

        $allTerms = array_unique(array_merge(array_keys($vecA), array_keys($vecB)));

        foreach ($allTerms as $term) {
            $a = $vecA[$term] ?? 0;
            $b = $vecB[$term] ?? 0;
            $dotProduct += $a * $b;
            $magA += $a * $a;
            $magB += $b * $b;
        }

        $magnitude = sqrt($magA) * sqrt($magB);
        return $magnitude == 0 ? 0 : $dotProduct / $magnitude;
    }
}
