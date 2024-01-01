<?php

namespace App\Service\Upload;

class UploadServices
{
    public function uploadImage($file, $url): string
    {
        $fileName = uniqid();

        $file->move(public_path($url), $fileName);
        return '/' . $url . '/' . $fileName;
    }
}
