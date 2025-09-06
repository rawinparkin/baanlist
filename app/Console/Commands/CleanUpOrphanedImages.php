<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Gallery;
use Carbon\Carbon;

class CleanUpOrphanedImages extends Command
{
    protected $signature = 'images:cleanup-orphans';
    protected $description = 'Delete unused uploaded images from temp_ids that are older than X hours and not linked to real properties';

    public function handle()
    {
        $cutoff = Carbon::now()->subHours(12); // older than 12 hours

        // Get temp galleries not linked to actual property IDs
        $orphanGalleries = Gallery::where('created_at', '<', $cutoff)
            ->whereDoesntHave('property') // make sure the relationship is set up
            ->get();

        $tempIds = $orphanGalleries->pluck('property_id')->unique();

        foreach ($tempIds as $tempId) {
            $folder = public_path("upload/property/" . $tempId);

            if (File::exists($folder)) {
                File::deleteDirectory($folder);
                $this->info("Deleted folder: $folder");
            }

            // Delete DB records
            Gallery::where('property_id', $tempId)->delete();
        }

        $this->info("Cleaned up orphan images.");
        return 0;
    }
}
