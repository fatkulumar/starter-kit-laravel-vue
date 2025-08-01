<?php

namespace App\DataTransferObjects;

class UserDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly ?string $id,
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password,
        public readonly ?string $role,
        public readonly ?ProfileDTO $profile,
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            role: $data['role'] ?? null,
            profile: ProfileDTO::fromArray($data),
        );
    }
}
