<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Magazine;
use Carbon\Carbon;

#[Signature('fetch:watch-news')]
#[Description('Fetch the latest luxury watch news from Monochrome Watches RSS')]
class FetchWatchNews extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching news from Monochrome Watches RSS...');
        $url = 'https://monochrome-watches.com/feed/';
        
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
                if ($count >= 15) break;
                
                $title = (string)$item->title;
                $link = (string)$item->link;
                $pubDate = Carbon::parse((string)$item->pubDate);
                $source = 'Monochrome Watches';
                
                // Extract image from enclosure or media:content if available
                $imageUrl = null;
                $namespaces = $item->getNamespaces(true);
                
                if (isset($item->enclosure)) {
                    $imageUrl = (string)$item->enclosure['url'];
                } elseif (isset($namespaces['media'])) {
                    $media = $item->children($namespaces['media']);
                    if (isset($media->content)) {
                        $imageUrl = (string)$media->content->attributes()->url;
                    }
                }
                
                // Fallback to description html
                if (!$imageUrl) {
                    $description = (string)$item->description;
                    if (preg_match('/<img[^>]+src="([^">]+)"/i', $description, $matches)) {
                        $imageUrl = $matches[1];
                    }
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
