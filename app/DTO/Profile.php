<?php

namespace App\DTO;

class Profile
{
    public function __construct(
        public readonly string|int $id,
        public readonly string $name,
    ) {

    }
}
