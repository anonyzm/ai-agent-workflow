<?php

namespace App\Interface;

const TASK_STATUS_OPEN = 'open';
const TASK_STATUS_IN_PROGRESS = 'in_progress';
const TASK_STATUS_DONE = 'done';
const TASK_STATUS_CANCELLED = 'cancelled';

interface TaskTrackerInterface
{
    public function getTask(string $taskKey): array;
}
