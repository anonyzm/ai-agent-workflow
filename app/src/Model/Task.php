<?php

namespace App\Model;

class Task
{
    public function __construct(
        public string $key = '',
        public string $title = '',
        public string $description = '',
        public string $status = '',
        public array $tags = []
    ) {}

    public function fromArray(array $data): self
    {
        // TODO: Реализовать метод
        return new self(
            'TEST-1',
            'This is a test task',
            'This is a test description',
            'todo',
            ['my-test-tag', 'my-test-tag2']
        );
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'tags' => $this->tags
        ];
    }
}