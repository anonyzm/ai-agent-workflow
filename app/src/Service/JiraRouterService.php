<?php
namespace App\Service;

use App\Interface\TaskTrackerInterface;
use Psr\Log\LoggerInterface;

class JiraRouterService implements TaskRouterInterface
{
    public function __construct(
        private readonly TaskTrackerInterface $trackerService,
        private readonly LoggerInterface $logger
    ) {}

    public function routeTask(array $arrayData): void
    {
        $this->logger->info('JiraRouterService: routeTask', ['arrayData' => $arrayData]);

        // TODO: ЗАКОНЧИЛ ТУТ, НАДО ДОДЕЛАТЬ РАБОТУ С ТЕМПОРАЛ
        $workflow = $this->workflowClient->newWorkflowStub(
            GreetingWorkflowInterface::class,
            WorkflowOptions::new()->withWorkflowExecutionTimeout(CarbonInterval::minute())
        );

        $output->writeln("Starting <comment>GreetingWorkflow</comment>... ");

        // Start a workflow execution. Usually this is done from another program.
        // Uses task queue from the GreetingWorkflow @WorkflowMethod annotation.
        $run = $this->workflowClient->start($workflow, 'Antony');

        $output->writeln(
            sprintf(
                'Started: WorkflowID=<fg=magenta>%s</fg=magenta>',
                $run->getExecution()->getID(),
            )
        );

        // getResult waits for workflow to complete
        $output->writeln(sprintf("Result:\n<info>%s</info>", $run->getResult()));
        
    }
}