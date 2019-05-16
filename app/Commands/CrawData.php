<?php
namespace App\Commands;

use \TypeRocket\Console\Command;

class CrawData extends Command
{

    protected $command = [
        'app:crawl-data',
        'Short description here',
        'Longer help text goes here.',
    ];

    protected function config()
    {
        // If you want to accept arguments
        // $this->addArgument('arg', self::REQUIRED, 'Description');
    }

    public function exec()
    {
        // When command executes
        $this->success('Execute!');
    }
}