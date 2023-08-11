<?php

namespace App\DTO;

class Project
{
    public function __construct(
        public readonly string|int $id,
        public readonly string $name,
    ) {

    }
}
