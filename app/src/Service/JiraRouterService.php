<?php
namespace App\Service;

use App\Interface\TaskTrackerInterface;
use App\Interface\TaskRouterInterface;
use App\Workflow\AnalyzeTaskWorkflowInterface;
use Psr\Log\LoggerInterface;    
use Temporal\Client\WorkflowClient;
use Temporal\Client\WorkflowOptions;
use Carbon\CarbonInterval;
use App\Model\Task;

class JiraRouterService implements TaskRouterInterface
{
    const DOABLE_STATUS_ID = '10000';
    const DOABLE_TYPE_ID = '10100';
    const DOABLE_PROJECT_ID = '10000';
    const DOABLE_TAG = 'ai-tag';

    const ASSIGNEE_ID = '10300';


    public function __construct(
        private readonly TaskTrackerInterface $trackerService,
        private readonly WorkflowClient $workflowClient,
        private readonly LoggerInterface $logger
    ) {}

    public function routeTask(Task $task): void
    {
        $this->logger->info('JiraRouterService: routeTask', ['task' => $task]);

        $workflow = $this->workflowClient->newWorkflowStub(
            AnalyzeTaskWorkflowInterface::class,
            WorkflowOptions::new()->withWorkflowExecutionTimeout(CarbonInterval::minute())
        );

        $this->logger->info('[routeTask:begin]', ['task' => $task]);

        // Start a workflow execution. Usually this is done from another program.
        // Uses task queue from the GreetingWorkflow @WorkflowMethod annotation.
        $run = $this->workflowClient->start($workflow, 'Antony');

        $this->logger->info(sprintf(
                'Started: WorkflowID=<fg=magenta>%s</fg=magenta>',
                $run->getExecution()->getID(),
            ));

        // getResult waits for workflow to complete
        $this->logger->info('[routeTask:end]', ['task' => $task]);
       
    }

    private function isDoable(Task $task): bool 
    {
        return $task->status->id === self::DOABLE_STATUS_ID 
            && $task->type->id === self::DOABLE_TYPE_ID
            && $task->project->id === self::DOABLE_PROJECT_ID
            && in_array(self::DOABLE_TAG, $task->tags);
    }
}