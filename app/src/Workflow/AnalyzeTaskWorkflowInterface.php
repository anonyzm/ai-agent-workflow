<?php

namespace App\Workflow;

use App\Model\Task;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface AnalyzeTaskWorkflowInterface {

    #[WorkflowMethod]
     public function analyzeTask(Task $task): void;
}