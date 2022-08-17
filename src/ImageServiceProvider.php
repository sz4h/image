<?php

namespace Space\Image;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Space\Image\Commands\ImageCommand;

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
