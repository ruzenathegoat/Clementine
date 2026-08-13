<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SyncToR2Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'r2:sync {--force : Force sync even if file exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync public static assets (images, hero-sequence) to Cloudflare R2';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $r2 = Storage::disk('r2');
        $force = $this->option('force');

        $mapping = [
            'images/products' => 'products',
            'hero-sequence' => 'hero',
            'magazine' => 'magazine',
        ];

        $this->info('Starting sync to Cloudflare R2 with specific folder mapping...');

        $syncedCount = 0;
        $skippedCount = 0;

        foreach ($mapping as $localDir => $targetDir) {
            $localPath = public_path($localDir);
            
            if (!File::isDirectory($localPath)) {
                $this->warn("Directory public/{$localDir} does not exist. Skipping.");
                continue;
            }

            $files = File::allFiles($localPath);
            
            $this->info("Found " . count($files) . " files in public/{$localDir}. Syncing to R2 folder '{$targetDir}'...");
            
            $bar = $this->output->createProgressBar(count($files));
            $bar->start();

            foreach ($files as $file) {
                $relativePathname = str_replace('\\', '/', $file->getRelativePathname());
                
                // Map to target directory
                $relativePath = $targetDir . '/' . $relativePathname;
                
                // Special case for watchmaker notes
                if ($targetDir === 'products' && in_array($relativePathname, ['Balance-wheel.webp', 'sapphire-crystal.jpg'])) {
                    $relativePath = 'wm_notes/' . $relativePathname;
                }
                
                // Check if file already exists in R2
                if (!$force && $r2->exists($relativePath)) {
                    $skippedCount++;
                    $bar->advance();
                    continue;
                }

                // Upload with correct cache-control for immutable static assets
                $r2->put($relativePath, File::get($file->getRealPath()), [
                    'visibility' => 'public',
                    'CacheControl' => 'public, max-age=31536000, immutable'
                ]);

                $syncedCount++;
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
        }

        $this->info("Sync complete!");
        $this->info("Successfully uploaded: {$syncedCount} files.");
        $this->info("Skipped (already exists): {$skippedCount} files.");
        
        if (empty(config('filesystems.disks.r2.url'))) {
            $this->warn('Notice: R2_URL is not set in your .env. cdn_asset() will still serve from local.');
        } else {
            $this->info('Your site will now serve these assets from: ' . config('filesystems.disks.r2.url'));
        }
    }
}
