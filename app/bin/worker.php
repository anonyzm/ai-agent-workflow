#!/usr/bin/env php
<?php

use Temporal\WorkerFactory;
use App\Kernel\Kernel;
use Temporal\DataConverter\DataConverter;
use Temporal\DataConverter\NullConverter;
use Temporal\DataConverter\BinaryConverter;
use App\Temporal\DeclarationLocator;

require __DIR__.'/../vendor/autoload.php';
ini_set('display_errors', 'stderr');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

// Получаем контейнер
$container = $kernel->getContainer();

// factory initiates and runs task queue specific activity and workflow workers
$factory = WorkerFactory::create(new DataConverter(
    new NullConverter(),
    new BinaryConverter(),
    new \App\Temporal\JsonConverter()
));

// Worker that listens on a Task Queue and hosts both workflow and activity implementations.
$worker = $factory->newWorker();

$declarations = DeclarationLocator::create(dirname(__DIR__) . '/src/Workflow/');

foreach ($declarations->getWorkflowTypes() as $workflowType) {
    // Workflows are stateful. So you need a type to create instances.
    $worker->registerWorkflowTypes($workflowType);
}

foreach ($declarations->getActivityTypes() as $activityType) {
    // Activities are stateless and thread safe. So a shared instance is used.
    $worker->registerActivity($activityType);
}

// $worker->registerWorkflowTypes(App\Workflow\AnalyzeTaskWorkflowInterface::class);
// $worker->registerActivity(App\Workflow\AnalyzeTaskActivity::class);

// start primary loop
$factory->run();