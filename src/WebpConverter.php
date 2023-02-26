<?php

namespace Cyberbrains\Filemanager;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

class WebpConverter
{
    public function storePoster(UploadedFile $file, string $fileName): \Intervention\Image\Image
    {
        return Image::make($file)
            ->encode('webp')
            ->save($fileName);
    }
}