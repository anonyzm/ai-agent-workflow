<?php

namespace App\Interface;

// const TASK_STATUS_TODO = 'todo';
// const TASK_STATUS_IN_PROGRESS = 'in_progress';
// const TASK_STATUS_REVIEW = 'review';
// const TASK_STATUS_QA = 'qa';
// const TASK_STATUS_DONE = 'done';
// const TASK_STATUS_CANCELLED = 'cancelled';

// const TASK_TYPE_ANALYTICS = 'analytics';
// const TASK_TYPE_DEVELOP = 'task';

interface TaskRouterInterface
{
    public function routeTask(array $arrayData): void;
}
