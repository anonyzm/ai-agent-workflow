<?php

namespace App\Interface;

use App\Model\Task;

interface TaskRouterInterface
{
    public function routeTask(Task $task): void;
}
