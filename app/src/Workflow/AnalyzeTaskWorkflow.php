<?php

namespace App\Workflow;

use App\Model\Task;
use Temporal\Workflow;
use Carbon\CarbonInterval;
use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;

class AnalyzeTaskWorkflow implements AnalyzeTaskWorkflowInterface
{
    private $analyzeTaskActivity;

    public function __construct()
    {
        $this->analyzeTaskActivity = Workflow::newActivityStub(
            AnalyzeTaskActivityInterface::class,
            ActivityOptions::new()
                ->withStartToCloseTimeout(CarbonInterval::seconds(2))
                ->withRetryOptions(RetryOptions::new()->withMaximumAttempts(1))
        );
    }

    public function analyzeTask(Task $task): void 
    {
        $this->analyzeTaskActivity->commentStartTask($task);
        $devTask = $this->analyzeTaskActivity->analyzeTask($task);
        $devTask = $this->analyzeTaskActivity->createDevTask($task);
        $this->analyzeTaskActivity->commentFinishTask($task, $devTask);
        $this->analyzeTaskActivity->changeTaskStatus($task);
    }
}