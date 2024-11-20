<?php

namespace App\Modules\Articles\Console;

use App\Modules\Articles\ArticleSynchronizer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('articles:sync', "Synchronize articles")]
class ImportArticlesCommand extends Command
{
    public function __construct(
        private readonly ArticleSynchronizer $synchronizer,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->synchronizer->synchronize();

        return Command::SUCCESS;
    }
}
