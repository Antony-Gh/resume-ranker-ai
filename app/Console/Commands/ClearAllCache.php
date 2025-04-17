<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearAllCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all Laravel caches including config, route, view, event, and compiled files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing all Laravel caches...');

        $commands = [
            'cache:clear',
            'config:clear',
            'route:clear',
            'view:clear',
            'event:clear',
            'clear-compiled'
        ];

        foreach ($commands as $command) {
            $this->info("Running {$command}...");
            $this->call($command);
        }

        $this->info('All caches cleared successfully!');
    }
} 