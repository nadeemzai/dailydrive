<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class BackfillCategories extends Command
{
    protected $signature = 'blog:backfill-categories {--limit=0}';
    protected $description = 'Assign categories to articles that have none, using title/URL keyword matching.';

    // Keyword → Category (order matters — first match wins)
    protected array $keywords = [
        // AI first (most specific)
        'artificial intelligence' => 'Artificial Intelligence',
        'machine learning'        => 'Artificial Intelligence',
        'generative ai'           => 'Artificial Intelligence',
        'large language'          => 'Artificial Intelligence',
        'openai'                  => 'Artificial Intelligence',
        'anthropic'               => 'Artificial Intelligence',
        'deepmind'                => 'Artificial Intelligence',
        'chatgpt'                 => 'Artificial Intelligence',
        'gemini'                  => 'Artificial Intelligence',
        ' llm'                    => 'Artificial Intelligence',
        'gpt-'                    => 'Artificial Intelligence',
        'ai '                     => 'Artificial Intelligence',
        ' ai-'                    => 'Artificial Intelligence',
        '-ai-'                    => 'Artificial Intelligence',
        'amodei'                  => 'Artificial Intelligence',
        'nvidia'                  => 'Artificial Intelligence',

        // Security
        'cybercriminal'  => 'Security',
        'breach'         => 'Security',
        'hack'           => 'Security',
        'ransomware'     => 'Security',
        'malware'        => 'Security',
        'vulnerability'  => 'Security',
        'cybersecurity'  => 'Security',
        'phishing'       => 'Security',
        'exploit'        => 'Security',
        'zero-day'       => 'Security',
        'privacy'        => 'Security',

        // Environment
        'climate'       => 'Environment',
        'environment'   => 'Environment',
        'renewable'     => 'Environment',
        'solar'         => 'Environment',
        'carbon'        => 'Environment',
        'emission'      => 'Environment',
        'battery'       => 'Environment',
        'electric vehicle' => 'Environment',
        ' ev '          => 'Environment',

        // Health
        'health'        => 'Health',
        'medical'       => 'Health',
        'biotech'       => 'Health',
        'vaccine'       => 'Health',
        'cancer'        => 'Health',
        'drug '         => 'Health',

        // Gaming
        'gaming'        => 'Gaming',
        'game'          => 'Gaming',
        'esport'        => 'Gaming',
        'playstation'   => 'Gaming',
        'xbox'          => 'Gaming',
        'nintendo'      => 'Gaming',

        // Science
        'space'         => 'Science',
        'nasa'          => 'Science',
        'quantum'       => 'Science',
        'biology'       => 'Science',
        'physics'       => 'Science',

        // Policy
        'regulation'    => 'Policy',
        'antitrust'     => 'Policy',
        'congress'      => 'Policy',
        'government'    => 'Policy',
        'senate'        => 'Policy',
        'european union'=> 'Policy',
        ' eu '          => 'Policy',
        'gdpr'          => 'Policy',
        'lawsuit'       => 'Policy',

        // Business
        'startup'       => 'Business',
        'funding'       => 'Business',
        'venture'       => 'Business',
        'ipo '          => 'Business',
        'acquisition'   => 'Business',
        'billion'       => 'Business',
        'million'       => 'Business',
        'amazon'        => 'Business',
        'apple '        => 'Business',
        'google'        => 'Business',
        'microsoft'     => 'Business',
        'tesla'         => 'Business',
        'meta '         => 'Business',
        'outsourcing'   => 'Business',

        // Technology (broad catch-all)
        'software'      => 'Technology',
        'hardware'      => 'Technology',
        'smartphone'    => 'Technology',
        'robot'         => 'Technology',
        'chip'          => 'Technology',
        'semiconductor' => 'Technology',
        'internet'      => 'Technology',
        'cloud'         => 'Technology',
        'data center'   => 'Technology',
        'tech'          => 'Technology',
    ];

    public function handle(): int
    {
        $query = Article::whereNull('category');
        $limit = (int) $this->option('limit');
        if ($limit > 0) {
            $query->limit($limit);
        }

        $articles = $query->get();
        $this->info("Found {$articles->count()} articles without category.");

        $updated = 0;
        foreach ($articles as $article) {
            $text = strtolower(' ' . ($article->title ?? '') . ' ' . ($article->source_url ?? '') . ' ' . ($article->excerpt ?? '') . ' ');
            $category = null;
            foreach ($this->keywords as $kw => $cat) {
                if (str_contains($text, $kw)) {
                    $category = $cat;
                    break;
                }
            }
            if ($category) {
                $article->forceFill(['category' => $category])->save();
                $updated++;
                $this->line("  [{$article->id}] → {$category}  ({$article->title})");
            } else {
                $this->line("  [{$article->id}] → (no match)  ({$article->title})");
            }
        }

        $this->info("Updated {$updated} / {$articles->count()} articles.");
        return 0;
    }
}
