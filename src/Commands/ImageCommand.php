<?php

namespace Space\Image\Commands;

use Illuminate\Console\Command;

class ImageCommand extends Command
{
    public $signature = 'image';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
