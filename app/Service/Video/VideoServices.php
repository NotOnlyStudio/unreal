<?php

namespace App\Service\Video;

class VideoServices
{
    public function videoID(string $url): ?string
    {
        $pattern = '/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w\-]+)/';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return $url;
    }
}
