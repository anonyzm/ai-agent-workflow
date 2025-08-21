<?php

namespace App\Kernel;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\YamlFileLoader as RoutingYamlLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\FileLocator;
use Temporal\WorkerFactory;
use App\Workflow\AnalyzeTaskWorkflow;
use App\Workflow\AnalyzeTaskActivity;

class Kernel extends BaseKernel
{
    public function registerBundles(): array
    {
        return [
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        // Конфигурация загружается в buildContainer()
    }

    public function getProjectDir(): string
    {
        return dirname(__DIR__, 2);
    }

    public function getCacheDir(): string
    {
        return $this->getProjectDir() . '/cache';
    }

    public function getLogDir(): string
    {
        return $this->getProjectDir() . '/logs';
    }

    public function loadRoutes(): RouteCollection
    {
        $fileLocator = new FileLocator($this->getProjectDir() . '/config');
        $loader = new RoutingYamlLoader($fileLocator);
        return $loader->load('routes.yaml');
    }

    protected function buildContainer(): ContainerBuilder
    {
        $container = parent::buildContainer();
        
        $container->setParameter('kernel.project_dir', $this->getProjectDir());
        $container->setParameter('kernel.cache_dir', $this->getCacheDir());
        $container->setParameter('kernel.logs_dir', $this->getLogDir());

        // Загружаем сервисы из YAML
        $fileLocator = new FileLocator($this->getProjectDir() . '/config');
        $yamlLoader = new YamlFileLoader($container, $fileLocator);
        
        // Сначала загружаем основные сервисы
        $yamlLoader->load('services.yaml');
        
        // Затем загружаем конфигурацию пакетов
        if (file_exists($this->getProjectDir() . '/config/packages/monolog.yaml')) {
            $yamlLoader->load('packages/monolog.yaml');
        }
                
        return $container;
    }
    
    public function startWorkers(): void
    {
        // factory initiates and runs task queue specific activity and workflow workers
        $factory = WorkerFactory::create();
        // Worker that listens on a Task Queue and hosts both workflow and activity implementations.
        $worker = $factory->newWorker();
        // Workflows are stateful. So you need a type to create instances.
        $worker->registerWorkflowTypes(AnalyzeTaskWorkflow::class);
        // Activities are stateless and thread safe. So a shared instance is used.
        $worker->registerActivity(AnalyzeTaskActivity::class);
        // start primary loop
        $factory->run();
    }
} 