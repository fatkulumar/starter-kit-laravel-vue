<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUpload
{
    public $settings = [
        'attributes'  => ['file'],
        'path'        => 'files/',
        'softdelete'  => true
    ];

    /**
     * add fileSettings method in model (required)
     *
     * @return [
     *      'attributes'  => ['{file_attribute_name}'],
     *      'path'        => '{file_path}',
     *      'softdelete'  => '{boolean}'
     * ];
     */
    abstract protected function fileSettings();

    protected function initializeFileTrait()
    {
        $this->settings = array_merge($this->settings, $this->fileSettings());
    }

    public static function bootFileTrait()
    {
        static::saving(function ($model) {

            foreach ($model->settings['attributes'] as $attribute) {
                if ($model->{$attribute} instanceof UploadedFile) {
                    $file    = $model->{$attribute};
                    $fileName = $model->storeFile($attribute, $file);

                    $model->{$attribute} = $fileName;
                }
            }

        });

        static::deleting(function ($model) {
            $model->deleteAllFiles();
        });
    }

    public function uploadFile($file)
    {
        // if ($file instanceof UploadedFile) {
        //     $fileName = "{$file->hashName()}-{$file->getClientOriginalName()}";
        //     $file->storeAs('public/' .$this->settings['path'], $fileName); // simpan ke storage
        //     return $fileName;
        // }
        // return false;

        // $fileName = "{$file->hashName()}-{$file->getClientOriginalName()}";
        // $file->move(public_path($this->settings['path']), $fileName);//simpan ke public
        // return $fileName;

        //simpan ke public
        if ($file instanceof UploadedFile) {
            $originalName = $file->getClientOriginalName();
            $cleanName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
            $extension = $file->getClientOriginalExtension();
            $fileName = "{$cleanName}-" . time() . ".{$extension}";

            $destination = public_path($this->settings['path']);

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $fileName);

            return $fileName;
        }
        return false;
    }

    public function getFileAttribute($value)
    {
        if ($value instanceof UploadedFile) return $value;

        return $value ?
            // asset('storage/app/' . $this->settings['path'] . $value) : //get path storege
            asset('storage/' . $this->settings['path'] . $value) : // get path public
            null;
    }

    protected function storeFile($attribute, $file)
    {
        $this->deleteFile($attribute);

        $fileName = $file->hashName();
        $file->storeAs($this->settings['path'], $fileName);

        return $fileName;
    }

    public function deleteFile($attribute)
    {
        // storage
        // if ($this->settings['softdelete'] === false) {

        //     return Storage::delete('public/'. $this->settings['path'] . $attribute);
        //     // return true;

        // }
        // return false;

        //public

        $filePath = public_path($this->settings['path'] . $attribute);
        if (file_exists($filePath)) {
            unlink($filePath);
            return true;
        }
        return false;
    }

    protected function deleteAllFiles()
    {
        if ($this->settings['softdelete'] === false){

            foreach ($this->settings['attributes'] as $attribute) {
                $this->deleteFile($attribute);
            }
        }
    }

    public function isFileExists($attribute)
    {
        // public
        $path = public_path($this->settings['path'] . $attribute);
        return file_exists($path);
    }
}