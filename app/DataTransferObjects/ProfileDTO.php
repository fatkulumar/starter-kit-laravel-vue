<?php

namespace App\DataTransferObjects;

class ProfileDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(public readonly mixed $photo) {}
    public static function fromArray(array $data): self
    {
        return new self(
            photo: $data['photo'] ?? null,
        );
    }
}
