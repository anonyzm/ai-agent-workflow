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
        private readonly TaskRouterInterface $routerService
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

        $data = json_decode($request->getContent(), true);
        $this->routerService->routeTask($data);

        return new JsonResponse([
            'message' => 'Thanks for callback',
            'timestamp' => date('Y-m-d H:i:s')
        ], 200);
    }
}