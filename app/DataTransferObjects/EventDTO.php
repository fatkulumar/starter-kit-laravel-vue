<?php

namespace App\DataTransferObjects;

use Illuminate\Http\UploadedFile;

class EventDTO
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $title,
        public readonly ?string $description,
        public readonly UploadedFile|string|null $banner,
        public readonly string $start_time,
        public readonly string $end_time,
        public readonly string $registration_deadline,
        public readonly string $preliminary_date,
        public readonly string $final_date,
        public readonly string $whatsapp_group_link,
        public readonly string $guidebook_link,
        public readonly string $location,
        public readonly bool $is_online,
        public readonly ?string $link_zoom,
        public readonly ?int $quota,
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'],
            description: $data['description'] ?? null,
            banner: $data['banner'] ?? null,
            start_time: $data['start_time'],
            end_time: $data['end_time'],
            registration_deadline: $data['registration_deadline'],
            preliminary_date: $data['preliminary_date'],
            final_date: $data['final_date'],
            whatsapp_group_link: $data['whatsapp_group_link'],
            guidebook_link: $data['guidebook_link'],
            location: $data['location'],
            is_online: (bool) ($data['is_online'] ?? true),
            link_zoom: $data['link_zoom'] ?? null,
            quota: isset($data['quota']) ? (int) $data['quota'] : null,
        );
    }
}
