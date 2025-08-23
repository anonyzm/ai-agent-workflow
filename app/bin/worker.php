#!/usr/bin/env php
<?php

use Temporal\WorkerFactory;
use App\Kernel\Kernel;

require __DIR__.'/../vendor/autoload.php';
ini_set('display_errors', 'stderr');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

// Получаем контейнер
$container = $kernel->getContainer();

// factory initiates and runs task queue specific activity and workflow workers
$factory = WorkerFactory::create();

// Worker that listens on a Task Queue and hosts both workflow and activity implementations.
$worker = $factory->newWorker();

// Workflows are stateful. So you need a type to create instances.
$worker->registerWorkflowTypes(App\Workflow\AnalyzeTaskWorkflow::class);

// Activities are stateless and thread safe. So a shared instance is used.
$worker->registerActivity(App\Workflow\AnalyzeTaskActivity::class);

// start primary loop
$factory->run();