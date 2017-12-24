<?php

namespace MyShop\AdminBundle\ImageUtils;
use MyShop\AdminBundle\DTO\UploadedImageResult;

class RemoveFiles
{
    private $imageUploadDir;
    public function __construct($imageUploadDir)
    {
        $this->imageUploadDir = $imageUploadDir;
    }

    public function removeFiles(array $photoArray)
    {
        foreach ($photoArray as $value) {
            if (file_exists($this->imageUploadDir . $value)) {
                unlink($this->imageUploadDir . $value);
            }
        }
    }
}