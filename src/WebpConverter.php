<?php

namespace Cyberbrains\Filemanager;

use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class WebpConverter
{
    public function storePoster(UploadedFile $file, string $fileName): \Intervention\Image\Image
    {
        return Image::make($file)
            ->encode('webp')
            ->save($fileName);
    }
}