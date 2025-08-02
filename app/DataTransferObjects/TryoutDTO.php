<?php

namespace App\DataTransferObjects;

use Illuminate\Http\UploadedFile;

class TryoutDTO
{
    public function __construct(
        public readonly ?string $id,
        public readonly UploadedFile|string|null $thumbnail,
        public readonly ?string $event_id,
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $start_time,
        public readonly string $end_time,
        public readonly int $duration,
        public readonly bool $is_active,
        public readonly bool $is_locked,
        public readonly ?string $guide_link,
        public readonly int $price,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            thumbnail: $data['thumbnail'] ?? null,
            event_id: $data['event_id'] ?? null,
            title: $data['title'],
            description: $data['description'] ?? null,
            start_time: $data['start_time'],
            end_time: $data['end_time'],
            duration: (int) $data['duration'],
            is_active: (bool) ($data['is_active'] ?? true),
            is_locked: (bool) ($data['is_locked'] ?? false),
            guide_link: $data['guide_link'] ?? null,
            price: (int) ($data['price'] ?? 0),
        );
    }
}
