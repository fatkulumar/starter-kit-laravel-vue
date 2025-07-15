<?php

namespace App\Http\Controllers\Admin\User\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserStoreRequest;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Models\User;
use App\Traits\FileUpload;
use App\Traits\ResultService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use ResultService;
    use FileUpload;

    /**
     * Trait FileUpload
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
     * Display a listing of the resource.
     */
    private $model = User::class;

    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $page = $request->query('page', 1);
        $cacheKey = 'users:search=' . ($search ?? 'all') . ':page=' . $page;
        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search) {
            return $this->model::with(['profile'])->filter($search)->paginate(10);
        });
        $this->setResult($data)->setStatus(true)->setMessage('Success Get Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $photo = $request->file('photo');
        $uploadPhoto = null;

        if ($photo) {
            $this->fileSettings();
            $uploadPhoto = $this->uploadFile($photo);
        }

        DB::beginTransaction();

        try {
            $user = $this->model::create($data);

            if ($request->filled('role')) {
                $user->assignRole($request->post('role'));
            }

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['photo' => $uploadPhoto ?? 'photo ' . $data['name']]
            );
            
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->setResult($e->getMessage())
            ->setStatus(false)
            ->setMessage('Failed Save Data')
            ->setCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            return $this->toJson();
        }

        Cache::flush();
        
        $user = $this->model::with(['profile'])->latest()->first();

        $this->setResult($user)
            ->setStatus(true)
            ->setMessage('Success Save Data')
            ->setCode(JsonResponse::HTTP_OK);

        return $this->toJson();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $user = $this->model::findOrFail($id);
        $user->fill($data);

        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $photo = $request->file('photo');
        $uploadPhoto = null;

        if ($photo) {
            $this->fileSettings();
             $this->deleteFile($user->profile->photo);
            $uploadPhoto = $this->uploadFile($photo);
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['photo' => $uploadPhoto ?? 'photo ' . $data['name']]
            );
        }

        $user->save();

        $user->syncRoles($request->post('role'));
        
        Cache::flush();

        $this->setResult($user)
            ->setStatus(true)
            ->setMessage('Success Update Data')
            ->setCode(JsonResponse::HTTP_OK);

        return $this->toJson();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    // : JsonResponse
    {
        $data = $this->model::findOrFail($id);
        $this->fileSettings();
        if ($data->profile && $this->isFileExists($data->profile->photo)) {
            $this->deleteFile($data->profile->photo);
        }
        $data->delete();
        Cache::flush();
        $this->setResult($id)->setStatus(true)->setMessage('Success Delete Data')->setCode(JsonResponse::HTTP_OK);
        return $this->toJson();
    }
}
