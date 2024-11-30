<?php

namespace App\Utils;

class CustomMimeTypes
{
    protected $mimeTypes;

    public function __construct()
    {
        $this->mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            // 必要に応じて他のMIMEタイプを追加
        ];
    }

    public function guessMimeType($extension)
    {
        return $this->mimeTypes[$extension] ?? 'application/octet-stream';
    }
}
