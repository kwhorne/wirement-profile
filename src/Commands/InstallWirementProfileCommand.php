<?php

namespace Wirement\Profile\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallWirementProfileCommand extends Command
{
    protected $signature = 'wirement-profile:install 
                            {--force : Overwrite existing files}
                            {--with-teams : Install team management features}
                            {--with-2fa : Install two-factor authentication}';

    protected $description = 'Install Wirement Profile package';

    public function handle(): int
    {
        $this->info('Installing Wirement Profile...');

        // Publish migrations
        $this->call('vendor:publish', [
            '--tag' => 'wirement-profile-migrations',
            '--force' => $this->option('force'),
        ]);

        if ($this->option('with-teams')) {
            $this->call('vendor:publish', [
                '--tag' => 'wirement-profile-team-migrations',
                '--force' => $this->option('force'),
            ]);
        }

        // Publish config
        $this->call('vendor:publish', [
            '--tag' => 'wirement-profile-config',
            '--force' => $this->option('force'),
        ]);

        // Publish views
        $this->call('vendor:publish', [
            '--tag' => 'wirement-profile-views',
            '--force' => $this->option('force'),
        ]);

        // Install two-factor authentication if requested
        if ($this->option('with-2fa')) {
            $this->info('Installing Two-Factor Authentication...');
            $this->call('filament-two-factor-authentication:install');
        }

        // Run migrations
        if ($this->confirm('Would you like to run the migrations now?', true)) {
            $this->call('migrate');
        }

        $this->info('Wirement Profile installed successfully!');

        $this->newLine();
        $this->info('Next steps:');
        $this->line('1. Add the TwoFactorAuthenticatable trait to your User model');
        $this->line('2. Implement the HasPasskeys interface in your User model');
        $this->line('3. Add the WirementProfilePlugin to your Filament panel');

        return self::SUCCESS;
    }
}
