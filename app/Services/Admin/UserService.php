<?php

namespace App\Services\Admin;

use App\DataTransferObjects\UserDTO;
use App\Repositories\Admin\Profile\ProfileRepository;
use App\Repositories\Admin\User\UserRepository;
use App\Traits\FileUpload;
use App\Traits\ResultService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserService
// extends Service implements InterfaceService
{
    use ResultService;
    use FileUpload;
    private $userRepository, $profileRepository;

    /**
     * iniliazed from trait FileUpload.
     */
    protected function fileSettings()
    {
        $this->settings = [
            'attributes'  => ['jpeg', 'jpg', 'png'],
            'path'        => 'upload/profile/',
            'softdelete'  => false
        ];
    }

    /**
     * Create a new class instance.
     */
    public function __construct(UserRepository $userRepository, ProfileRepository $profileRepository)
    {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * List data paginate and search.
     */
    public function getUsers(array $payload): object
    {
        return $this->userRepository->getUsers($payload);
    }

    /**
     * Create data.
     */
    public function store(UserDTO $userDTO): object
    {
        $data = [
            'name' => $userDTO->name,
            'email' => $userDTO->email,
        ];

        if (!empty($userDTO->password)) {
            $data['password'] = bcrypt($userDTO->password);
        }

        $uploadPhoto = null;

        if ($userDTO->profile->photo) {
            $this->fileSettings();
            $uploadPhoto = $this->uploadFile($userDTO->profile->photo);
        }

        DB::beginTransaction();

        try {
            $user = $this->userRepository->store($data);

            if (!empty($userDTO->role)) {
                $user->assignRole($userDTO->role);
            }

            $this->profileRepository->updateOrCreate(
                ['user_id' => $user->id],
                ['photo' => $uploadPhoto ?? 'photo ' . $userDTO->name]
            );

            DB::commit();
            Cache::flush();

            return $this->userRepository->getUserWithProfileLatest($user->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $e;
        }
    }


    /**
     * update data.
     */
    public function update(UserDTO $dto): object
    {
        $user = $this->userRepository->show($dto->id);

        $updateData = [];

        if ($dto->name !== null) $updateData['name'] = $dto->names;
        if ($dto->email !== null) $updateData['email'] = $dto->email;
        if ($dto->password !== null) $updateData['password'] = bcrypt($dto->password);

        $user->fill($updateData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($dto->profile?->photo) {
            $this->fileSettings();

            if ($user->profile && $user->profile->photo) {
                $this->deleteFile($user->profile->photo);
            }

            $uploadPhoto = $this->uploadFile($dto->profile->photo);

            $this->profileRepository->updateOrCreate(
                ['user_id' => $user->id],
                ['photo' => $uploadPhoto ?? 'photo-' . ($dto->name ?? $user->name)]
            );
        }

        $user->save();

        if (!empty($dto->role)) {
            $user->syncRoles($dto->role);
        }

        Cache::flush();

        return $this->userRepository->getUserWithProfile($dto->id);
    }


    /**
     * delete one data.
     */
    public function delete(string $id): bool
    {
        $data = $this->userRepository->show($id);
        $this->fileSettings();
        if ($data->profile && $this->isFileExists($data->profile->photo)) {
            $this->deleteFile($data->profile->photo);
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
            $data = $this->userRepository->show($id);
            $this->fileSettings();
            if ($data->profile && $this->isFileExists($data->profile->photo)) {
                $this->deleteFile($data->profile->photo);
            }
            $data->delete($id);
        }
        Cache::flush();
        return $ids;
    }
}
