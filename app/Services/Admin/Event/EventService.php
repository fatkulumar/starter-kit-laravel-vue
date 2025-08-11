<?php

namespace App\Services\Admin\Event;

use App\DataTransferObjects\EventDTO;
use App\Repositories\Admin\Event\EventRepository;
use App\Services\Service;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\Cache;

class EventService extends Service implements EventServiceInterface
{
    use FileUpload;
    private $eventRepository;

    /**
     * iniliazed from trait FileUpload.
     */
    protected function fileSettings()
    {
        $this->settings = [
            'attributes'  => ['jpeg', 'jpg', 'png'],
            'path'        => 'upload/event/thumbnail/',
            'softdelete'  => false
        ];
    }

    /**
     * Create a new class instance.
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * List data paginate and search.
     */
    public function getEvents(array $payload): object
    {
        return $this->eventRepository->getEvents($payload);
    }

    /**
     * Create data.
     */
    public function store(EventDTO $dto): object
    {
        $data = [
            'title' => $dto->title,
            'description' => $dto->description,
            'start_time' => $dto->start_time,
            'end_time' => $dto->end_time,
            'registration_deadline' => $dto->registration_deadline,
            'preliminary_date' => $dto->preliminary_date,
            'final_date' => $dto->final_date,
            'whatsapp_group_link' => $dto->whatsapp_group_link,
            'guidebook_link' => $dto->guidebook_link,
            'location' => $dto->location,
            'link_zoom' => $dto->link_zoom,
            'quota' => $dto->quota,
            'is_publish' => $dto->is_publish,
        ];


        $uploadBanner = null;

        if ($dto->banner instanceof \Illuminate\Http\UploadedFile) {
            $this->fileSettings();
            $uploadBanner = $this->uploadFile($dto->banner);
        } else {
            $uploadBanner = null;
        }

        $data['banner'] = $uploadBanner;

        $eventRepository = $this->eventRepository->store($data);

        Cache::flush();

        return $this->eventRepository->getEventWithTryoutLatest($eventRepository->id);
    }


    /**
     * update data.
     */
    public function update(EventDTO $dto): object
    {
        $eventRepository = $this->eventRepository->show($dto->id);

        $updateData = [];

        if ($dto->title !== null) $updateData['title'] = $dto->title;
        $updateData['description'] = $dto->description;
        if ($dto->round !== null) $updateData['round'] = $dto->round;
        if ($dto->start_time !== null) $updateData['start_time'] = $dto->start_time;
        if ($dto->end_time !== null) $updateData['end_time'] = $dto->end_time;
        if ($dto->registration_deadline !== null) $updateData['registration_deadline'] = $dto->registration_deadline;
        $updateData['preliminary_date'] = $dto->preliminary_date;
        if ($dto->final_date !== null) $updateData['final_date'] = $dto->final_date;
        $updateData['whatsapp_group_link'] = $dto->whatsapp_group_link;
        $updateData['guidebook_link'] = $dto->guidebook_link;
        $updateData['location'] = $dto->location;
        if ($dto->is_online !== null) $updateData['is_online'] = $dto->is_online;
        $updateData['link_zoom'] = $dto->link_zoom;
        if ($dto->quota !== null) $updateData['quota'] = $dto->quota;
        if ($dto->is_publish !== null) $updateData['is_publish'] = $dto->is_publish;

        if ($dto->banner) {
            $this->fileSettings();

            if ($eventRepository->banner) {
                $this->deleteFile($eventRepository->banner);
            }

            $uploadBanner = $this->uploadFile($dto->banner);

            $updateData['banner'] = $uploadBanner;
        }

        $eventRepository->fill($updateData);
        $eventRepository->save();

        Cache::flush();

        return $this->eventRepository->getEventWithTryout($dto->id);
    }


    /**
     * delete one data.
     */
    public function delete(string $id): bool
    {
        $data = $this->eventRepository->show($id);
        $this->fileSettings();
        if ($data->banner && $this->isFileExists($data->banner)) {
            $this->deleteFile($data->banner);
        }
        Cache::flush();
        return $data->delete($id);
    }

    /**
     * delete many data.
     */
    public function destroy(array $ids): array
    {
        foreach ($ids as $id) {
            $data = $this->eventRepository->show($id);
            $this->fileSettings();
            if ($data->banner && $this->isFileExists($data->banner)) {
                $this->deleteFile($data->banner);
            }
            $data->delete($id);
        }
        Cache::flush();
        return $ids;
    }

    /**
     * find.
     */
    public function show(string $id): object
    {
        return $this->eventRepository->show($id);
    }

    /**
     * Find by event_code
     */
    public function findByEventCode(string $eventCode): object
    {
        return $this->eventRepository->findByEventCode($eventCode);
    }
}
