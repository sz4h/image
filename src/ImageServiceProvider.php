<?php

namespace Space\Image;

use Space\Image\Commands\ImageCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ImageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('image')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_image_table')
            ->hasCommand(ImageCommand::class);
    }
}
