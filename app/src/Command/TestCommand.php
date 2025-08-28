<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Interface\TaskRouterInterface;
use App\Model\Task;

#[AsCommand(
    name: 'app:test',
    description: 'Workflow test command',
)]
class TestCommand extends Command
{
    public function __construct(
        private readonly TaskRouterInterface $routerService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Запускаем тестовый роутинг задачи');

        $task = (new Task)->mock();
        $this->routerService->routeTask($task);
        
        $io->info('Выполнение закончено');        
        return Command::SUCCESS;
    }
}
