<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanTmpFiles extends Command
{
    protected $signature = 'tmp:clean';
    protected $description = 'Clean all files in tmp folder subdirectories';

    public function handle()
    {
        $tmpPath = '/var/www/storage/app/public/tmp';
        
        $this->info("Checking path: {$tmpPath}");
        
        if (!File::exists($tmpPath)) {
            $this->error("Tmp folder does not exist!");
            return 1;
        }

        $directories = File::directories($tmpPath);
        
        $this->info("Found " . count($directories) . " subdirectories");
        
        if (empty($directories)) {
            $this->warn('No subdirectories found');
            return 0;
        }

        $deletedCount = 0;

        foreach ($directories as $directory) {
            $files = File::files($directory);
            
            $this->info("Folder: " . basename($directory) . " - Files: " . count($files));
            
            foreach ($files as $file) {
                File::delete($file);
                $deletedCount++;
                $this->line(" Deleted: " . $file->getFilename());
            }
        }

        $this->info("Total deleted: {$deletedCount} files");
        return 0;
    }
}