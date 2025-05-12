<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeViewAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Ini yang akan kamu panggil: php artisan make:view-all
     */
    protected $signature = 'make:view-all';

    /**
     * The console command description.
     */
    protected $description = 'Generate all CRUD views with <x-app-layout> scaffold';

    public function handle()
    {
        // Daftar resource sesuai controller-mu
        $resources = ['admin','pelanggan','perawatan','pemesanan','pembayaran','booked'];
        // Method CRUD yang butuh view
        $views = ['index','create','edit','show'];

        foreach ($resources as $res) {
            foreach ($views as $view) {
                $dir  = resource_path("views/{$res}");
                $file = "{$dir}/{$view}.blade.php";

                // Pastikan direktori ada
                File::ensureDirectoryExists($dir);

                // Stub konten view
                $stub = <<<'BLADE'
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('{{RESOURCE_TITLE}} :: {{VIEW_TITLE}}') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <!-- TODO: Konten {{VIEW}} {{RESOURCE}} di sini -->
        </div>
    </div>
</x-app-layout>
BLADE;

                // Gantikan placeholder
                $stub = str_replace(
                    ['{{RESOURCE_TITLE}}','{{VIEW_TITLE}}','{{RESOURCE}}','{{VIEW}}'],
                    [Str::title($res), Str::title($view), $res, $view],
                    $stub
                );

                // Tulis file
                File::put($file, $stub);
                $this->info("Created view: {$res}/{$view}.blade.php");
            }
        }

        $this->info('All views generated successfully!');
        return 0;
    }
}
