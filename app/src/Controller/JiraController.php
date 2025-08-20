<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

class JiraController
{
    private $logger;

    public function __construct(LoggerInterface $logger) 
    {
        $this->logger = $logger;
    }

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
}