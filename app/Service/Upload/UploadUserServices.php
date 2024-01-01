<?php

namespace App\Service\Upload;

class UploadUserServices extends UploadServices
{
    public function upload(array $data): string
    {
        return $this->uploadImage($data['file'], 'uploads/' . $data['type']);
    }
}
