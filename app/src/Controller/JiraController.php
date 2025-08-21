<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;
use App\Interface\TaskTrackerInterface;

class JiraController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly TaskTrackerInterface $trackerService
    ) {}

    public function callback(Request $request): Response
    {       
        // Логируем входящий запрос
        $this->logger->info('Jira callback received', [
            'method' => $request->getMethod(),
            'path' => $request->getPathInfo(),
            'content' => $request->getContent(),
            'headers' => $request->headers->all()
        ]);

        return new JsonResponse([
            'message' => 'Thanks for callback',
            'timestamp' => date('Y-m-d H:i:s')
        ], 200);
    }

    public function getTask(Request $request): Response
    {
        $taskKey = $request->get('taskKey');
        $task = $this->trackerService->getTask($taskKey);
        return new JsonResponse($task);
    }
}