<?php

namespace Cyberbrains\Filemanager;

use Cyberbrains\Filemanager\Models\File;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class UploadFileService
{
    /**
     * @param UploadedFile $file
     * @return File|Exception
     * @throws Exception
     */
    public function upload($file): File|Exception
    {
        $name = $file->getClientOriginalName();
        $user_id = Auth::id();
        $path = '/storage/files/' . $user_id . '/';

        DB::beginTransaction();
        try {
            $link = $this->uploadFile($file, $user_id);
            $fileModel = $this->store($name, $file, $link, $path);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $fileModel;
    }

    /**
     * @param $file
     * @param $user_id
     * @return string
     */
    function uploadFile(UploadedFile $file, $user_id): string
    {
        $fileName = $file->getFilename();
        $type = strtok($file->getMimeType(), '/');

        if ($type == 'image') {
            $folderPath = storage_path() . '/app/public/files/' . $user_id . '/';
            $fullPath = $folderPath . $fileName . ".webp";

            if (!is_dir($folderPath)) {
                \Illuminate\Support\Facades\File::makeDirectory($folderPath);
            }
            $service = new WebpConverter();
            $service->storePoster($file, $fullPath);
            $link = env('STATIC_HOST') . $fileName . '.webp';
        } else {
            Storage::put('files/' . Auth::id());
            $link = env('STATIC_HOST') . $file->hashName();
        }
        return $link;
    }

    /**
     * @param $name
     * @param $file
     * @param $link
     * @param $path
     * @return File
     */
    function store($name, UploadedFile $file, $link, $path): File
    {
        $fileModel = new File();
        $fileModel->name = $name;
        $fileModel->ext = $file->getExtension();
        $fileModel->link = $link;
        $fileModel->user_id = Auth::id();
        $fileModel->size = $file->getSize();
        $fileModel->path = $path;
        $fileModel->domain = env('STATIC_HOST');
        $fileModel->upload_data = $file;
        $fileModel->save();

        return $fileModel;
    }

    /**
     * @throws Exception
     */
    public function multiUpload($files)
    {
        $uploads = [];
        foreach ($files as $file){
            $uploads[] = $this->upload($file);
        }
        return $uploads;

    }
}
