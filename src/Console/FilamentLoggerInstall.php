<?php

namespace TomatoPHP\FilamentLogger\Console;

use Illuminate\Console\Command;

class FilamentLoggerInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'filament-logger:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install package and publish assets';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Running migrations');
        $this->call('migrate', ['--force' => true]);

        $this->info('Filament Logger installed successfully.');

        return self::SUCCESS;
    }
}
