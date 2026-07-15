<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class TransitionNewProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:transition';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transition new products to active status when their scheduled drop time exceeds 40 minutes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updated = Product::where('status', 'new')
            ->whereNotNull('scheduled_publish_at')
            ->where('scheduled_publish_at', '<=', now()->subMinutes(40))
            ->update(['status' => 'active']);

        if ($updated > 0) {
            $this->info("Successfully transitioned {$updated} products to active status.");
        }
    }
}
