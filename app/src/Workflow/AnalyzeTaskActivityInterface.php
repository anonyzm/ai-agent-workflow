<?php

namespace App\Workflow;

use App\Model\Task;
use Temporal\Activity\ActivityInterface;

#[ActivityInterface]
interface AnalyzeTaskActivityInterface
{
    // пишем комментарий в таске
    public function commentStartTask(Task $task): void; 

    // обрабатываем таск в LLM, возвращаем таск для разработчика
    public function analyzeTask(Task $task): Task;

    // создаем таск для разработчика
    public function createDevTask(Task $devTask): Task;

    // пишем комментарий в таске со ссылкой на таск для разработчика
    public function commentFinishTask(Task $task, Task $devTask): void;

    // меняем статус таска на "завершен"
    public function changeTaskStatus(Task $task): Task;
}