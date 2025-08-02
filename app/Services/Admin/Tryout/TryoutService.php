<?php

namespace App\Services\Admin\Tryout;

use App\DataTransferObjects\TryoutDTO;
use App\Repositories\Admin\Event\EventRepository;
use App\Repositories\Admin\Tryout\TryoutRepository;
use App\Services\InterfaceService;
use App\Services\Service;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TryoutService extends Service implements TryoutServiceInterface
{
    use FileUpload;
    private $tryoutRepository;

    /**
     * iniliazed from trait FileUpload.
     */
    protected function fileSettings()
    {
        $this->settings = [
            'attributes'  => ['jpeg', 'jpg', 'png'],
            'path'        => 'upload/tryout/thumbnail/',
            'softdelete'  => false
        ];
    }

    /**
     * Create a new class instance.
     */
    public function __construct(TryoutRepository $tryoutRepository)
    {
        $this->tryoutRepository = $tryoutRepository;
    }

    /**
     * List data paginate and search.
     */
    public function getTryouts(array $payload): object
    {
        return $this->tryoutRepository->getTryouts($payload);
    }

    /**
     * Create data.
     */
    public function store(TryoutDTO $dto): object
    {
        $data = [
            'event_id'      => $dto->event_id,
            'title'         => $dto->title,
            'description'   => $dto->description,
            'start_time'    => $dto->start_time,
            'end_time'      => $dto->end_time,
            'duration'      => $dto->duration,
            'is_active'     => $dto->is_active,
            'is_locked'     => $dto->is_locked,
            'guide_link'    => $dto->guide_link,
            'price'         => $dto->price,
        ];

        $uploadThumbnail = null;

        if ($dto->thumbnail instanceof \Illuminate\Http\UploadedFile) {
            $this->fileSettings();
            $uploadThumbnail = $this->uploadFile($dto->thumbnail);
        }

        $data['thumbnail'] = $uploadThumbnail;

        // Simpan data tryout
        $tryout = $this->tryoutRepository->store($data);

        Cache::flush(); // Bersihkan cache agar data baru terbaca

        // Return detail data yang baru disimpan
        return $this->tryoutRepository->show($tryout->id);
    }



    /**
     * update data.
     */
    public function update(TryoutDTO $dto): object
    {
        $tryout = $this->tryoutRepository->show($dto->id);

        $updateData = [];

        if ($dto->event_id !== null) $updateData['event_id'] = $dto->event_id;
        if ($dto->title !== null) $updateData['title'] = $dto->title;
        if ($dto->description !== null) $updateData['description'] = $dto->description;
        if ($dto->start_time !== null) $updateData['start_time'] = $dto->start_time;
        if ($dto->end_time !== null) $updateData['end_time'] = $dto->end_time;
        if ($dto->duration !== null) $updateData['duration'] = $dto->duration;
        if ($dto->is_active !== null) $updateData['is_active'] = $dto->is_active;
        if ($dto->is_locked !== null) $updateData['is_locked'] = $dto->is_locked;
        if ($dto->guide_link !== null) $updateData['guide_link'] = $dto->guide_link;
        if ($dto->price !== null) $updateData['price'] = $dto->price;

        if ($dto->thumbnail instanceof \Illuminate\Http\UploadedFile) {
            $this->fileSettings();

            if ($tryout->thumbnail) {
                $this->deleteFile($tryout->thumbnail);
            }

            $updateData['thumbnail'] = $this->uploadFile($dto->thumbnail);
        }

        $tryout->fill($updateData)->save();

        Cache::flush();

        return $this->tryoutRepository->show($dto->id);
    }



    /**
     * delete one data.
     */
    public function delete(string $id): bool
    {
        $data = $this->tryoutRepository->show($id);
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
            $data = $this->tryoutRepository->show($id);
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
