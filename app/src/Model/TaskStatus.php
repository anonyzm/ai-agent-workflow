<?php

namespace App\Model;

use App\Temporal\ArrayableInterface;

class TaskStatus implements ArrayableInterface
{
    public function __construct(
        public string $id = '',
        public string $name = '',
    ) {}

    public function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? '',
            $data['name'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}