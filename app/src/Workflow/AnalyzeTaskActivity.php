<?php

namespace App\Workflow;

use App\Model\Task;
use Psr\Log\LoggerInterface;

class AnalyzeTaskActivity implements AnalyzeTaskActivityInterface
{

    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    public function commentStartTask(Task $task): void
    {
        $this->logger->info('Начали анализировать таск', ['task' => $task]);
    }

    public function analyzeTask(Task $task): Task
    { 
        $this->logger->info('Анализируем таск', ['task' => $task]);
        return $task;
    }

    public function createDevTask(Task $devTask): Task
    {
        $this->logger->info('Создаем таск для разработчика', ['devTask' => $devTask]);
        return $devTask;
    }

    public function commentFinishTask(Task $task, Task $devTask): void
    {
        $this->logger->info('Завершили анализ таска', ['task' => $task, 'devTask' => $devTask]);
    }

    public function changeTaskStatus(Task $task): Task
    {
        $this->logger->info('Меняем статус таска на "завершен"', ['task' => $task]);
        return $task;
    }
}