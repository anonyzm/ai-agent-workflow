<?php

namespace App\Temporal;

interface ArrayableInterface
{
    public function fromArray(array $data): self;
    public function toArray(): array;
}
