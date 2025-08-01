<?php

namespace App\Services\Admin\Event;

use App\DataTransferObjects\EventDTO;
use App\Repositories\Admin\Event\EventRepository;
use App\Services\InterfaceService;
use App\Services\Service;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EventService extends Service implements InterfaceService
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
        if ($dto->description !== null) $updateData['description'] = $dto->description;
        if ($dto->start_time !== null) $updateData['start_time'] = $dto->start_time;
        if ($dto->end_time !== null) $updateData['end_time'] = $dto->end_time;
        if ($dto->registration_deadline !== null) $updateData['registration_deadline'] = $dto->registration_deadline;
        if ($dto->preliminary_date !== null) $updateData['preliminary_date'] = $dto->preliminary_date;
        if ($dto->final_date !== null) $updateData['final_date'] = $dto->final_date;
        if ($dto->whatsapp_group_link !== null) $updateData['whatsapp_group_link'] = $dto->whatsapp_group_link;
        if ($dto->guidebook_link !== null) $updateData['guidebook_link'] = $dto->guidebook_link;
        if ($dto->location !== null) $updateData['location'] = $dto->location;
        if ($dto->is_online !== null) $updateData['is_online'] = $dto->is_online;
        if ($dto->link_zoom !== null) $updateData['link_zoom'] = $dto->link_zoom;
        if ($dto->quota !== null) $updateData['quota'] = $dto->quota;

        $eventRepository->fill($updateData);

        if ($dto->banner) {
            $this->fileSettings();

            if ($dto->banner && $eventRepository->banner) {
                $this->deleteFile($eventRepository->banner);
            }

            $uploadBanner = $this->uploadFile($dto->banner);

            if ($dto->banner !== null) {
                $updateData['banner'] = $uploadBanner;
            }
        }

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
    public function destroy(array $ids)
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
}
