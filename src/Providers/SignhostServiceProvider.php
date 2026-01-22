<?php

namespace Noardcode\LaravelSignhost\Providers;

use Aladei\Tenancy\Tenancy;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Noardcode\LaravelSignhost\Console\Commands\FakeSignhostIdProofWebhookCommand;
use Noardcode\LaravelSignhost\Console\Commands\FakeSignhostWebhookCommand;

/**
 * Class SignhostServiceProvider
 *
 */
class SignhostServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Tenancy
        Tenancy::addMigrationPath(__DIR__.'/../../database/migrations/tenants');

        $this->loadRoutesFrom($this->getPackageBasePath('/routes/web.php'));
        $this->mergeConfigFrom($this->getPackageBasePath('/config/disks.php'), 'filesystems.disks');

        $this->commands([
            FakeSignhostWebhookCommand::class,
            FakeSignhostIdProofWebhookCommand::class,
        ]);
    }

    /**
     * Merge the signhost configuration file with the application's configuration.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->getPackageBasePath('/config/signhost.php'), 'signhost');
    }

    /**
     * @param  string|null  $file
     * @return string
     */
    public function getPackageBasePath(?string $file = null): string
    {
        $path = __DIR__.'/../../';
        $path .= ! empty($file) ? ltrim($file, '/') : '';

        return $path;
    }
}
