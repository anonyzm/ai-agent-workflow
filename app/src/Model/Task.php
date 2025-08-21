<?php

namespace App\Model;

class Task
{
    public function __construct(
        public string $key,
        public string $title,
        public string $description,
        public string $status,
        public string $tags
    ) {}
}