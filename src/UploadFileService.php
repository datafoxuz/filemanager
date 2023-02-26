<?php

namespace Cyberbrains\Filemanager;

use Cyberbrains\Filemanager\WebpConverter;
use Cyberbrains\Filemanager\Models\File;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $fileName = $file->getFilename();
        $user_id = Auth::id();
        $type = strtok($file->getMimeType(), '/');
        $path = '/storage/files/' . Auth::id() . '/';


        DB::beginTransaction();
        try {
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
                $file->store('files/' . Auth::id());
                $link = env('STATIC_HOST') . $file->hashName();
            }


            $fileModel = new File();
            $fileModel->name = $name;
            $fileModel->ext = $file->extension();
            $fileModel->link = $link;
            $fileModel->user_id = Auth::id();
            $fileModel->size = $file->getSize();
            $fileModel->path = $path;
            $fileModel->domain = env('STATIC_HOST');
            $fileModel->upload_data = $file;
            $fileModel->save();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();

        return $fileModel;
    }
}
