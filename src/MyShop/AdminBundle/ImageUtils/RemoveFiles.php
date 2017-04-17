<?php

namespace MyShop\AdminBundle\ImageUtils;
use MyShop\AdminBundle\DTO\UploadedImageResult;

class RemoveFiles
{
    /**
     * @var array
     */
    private $photoArray = [];
    private $imageUploadDir;

    public function __construct($imageUploadDir)
    {
        $this->imageUploadDir = $imageUploadDir;
    }

    public function setPhotoArray($photoArray)
    {
        $this->photoArray = $photoArray;
    }

    public function removeFiles()
    {
        foreach ($this->photoArray as $value) {
            if (file_exists($this->imageUploadDir . $value)) {
                unlink($this->imageUploadDir . $value);
            }
        }
    }
}