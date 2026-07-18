<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Magazine;
use Carbon\Carbon;

#[Signature('fetch:watch-news')]
#[Description('Fetch the latest luxury watch news from Google News RSS')]
class FetchWatchNews extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching news from Google News RSS...');
        $url = 'https://news.google.com/rss/search?q=luxury+watches&hl=en-US&gl=US&ceid=US:en';
        
        try {
            $response = Http::get($url);
            
            if (!$response->successful()) {
                $this->error('Failed to fetch RSS feed.');
                return;
            }

            $xml = simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
            
            if (!$xml || !isset($xml->channel->item)) {
                $this->error('Failed to parse XML.');
                return;
            }

            $count = 0;
            foreach ($xml->channel->item as $item) {
                if ($count >= 15) break; // Limit to 15 items
                
                $title = (string)$item->title;
                $link = (string)$item->link;
                $pubDate = Carbon::parse((string)$item->pubDate);
                $source = isset($item->source) ? (string)$item->source : 'Google News';
                
                // Try to extract image from description HTML
                $description = (string)$item->description;
                $imageUrl = null;
                if (preg_match('/<img[^>]+src="([^">]+)"/i', $description, $matches)) {
                    $imageUrl = $matches[1];
                }

                Magazine::updateOrCreate(
                    ['link' => $link],
                    [
                        'title' => $title,
                        'image_url' => $imageUrl,
                        'source' => $source,
                        'pub_date' => $pubDate,
                    ]
                );
                
                $count++;
            }

            $this->info("Successfully fetched and processed {$count} articles.");
        } catch (\Exception $e) {
            $this->error('Error occurred: ' . $e->getMessage());
        }
    }
}
