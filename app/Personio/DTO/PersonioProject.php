<?php

namespace App\Personio\DTO;

use Illuminate\Contracts\Support\Arrayable;

class PersonioProject implements Arrayable
{
    private function __construct(
        public int $id,
        public string $name,
    ) {

    }

    public static function make(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['attributes']['name'],
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
