#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\ConsoleOutput;
use App\Kernel\Kernel;
use App\Kernel\DeclarationLocator;

require __DIR__.'/../vendor/autoload.php';

try {
    // Создаем экземпляр Kernel
    $kernel = new Kernel('dev', true);
    
    // Запускаем Kernel для инициализации контейнера
    $kernel->boot();
    
    // Получаем контейнер из Kernel
    $container = $kernel->getContainer();
    
    // Создаем экземпляр приложения
    $application = new Application('AI Agent Workflow Console', '1.0.0');

    // Регистрируем команды
    $declarations = DeclarationLocator::create(dirname(__DIR__) . '/src/Command/');
    foreach ($declarations->getAvailableDeclarations() as $class) {
        /** @var \ReflectionClass $class */
        $command = $container->get($class->name);
        $application->add($command);
    }
    
    // Запускаем приложение
    $application->run();
    
} catch (\Exception $e) {
    $output = new ConsoleOutput();
    $output->writeln('<error>Ошибка инициализации консоли: ' . $e->getMessage() . '</error>');
    $output->writeln('<error>Stack trace: ' . $e->getTraceAsString() . '</error>');
    exit(1);
}
