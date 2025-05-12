<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

class ModelList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all Eloquent model classes in app/Models';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $modelPath = app_path('Models');

        if (!is_dir($modelPath)) {
            $this->error("Directory 'app/Models' tidak ditemukan.");
            return 1;
        }

        $finder = new Finder;
        $finder->files()->in($modelPath)->name('*.php');

        $models = [];

        foreach ($finder as $file) {
            // Dapatkan path relatif, ubah menjadi namespace
            $relative = $file->getRelativePathname(); // e.g. "Admin/User.php" atau "Post.php"
            $class = str_replace(['/', '.php'], ['\\', ''], $relative);
            $models[] = "App\\Models\\{$class}";
        }

        if (empty($models)) {
            $this->info('Tidak ada model ditemukan di app/Models.');
        } else {
            $this->info('Daftar Model:');
            foreach ($models as $m) {
                $this->line(" - {$m}");
            }
        }

        return 0;
    }
}
