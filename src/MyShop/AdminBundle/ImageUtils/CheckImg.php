<?php

namespace MyShop\AdminBundle\ImageUtils;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class CheckImg
{
    /*
    array (size=2)
      0 =>
        array (size=2)
          0 => string 'jpg' (length=3)
          1 => string 'image/jpg' (length=9)
      1 =>
        array (size=2)
          0 => string 'gif' (length=3)
          1 => string 'image/gif' (length=9)
     * */
    private $supportImageTypeList;

    public function __construct($imageTypeList)
    {
        $this->supportImageTypeList = $imageTypeList;
    }

    public function check(UploadedFile $photoFile)
    {
        $checkTrue = false;
        $mimeType = $photoFile->getClientMimeType();
        foreach ($this->supportImageTypeList as $imgType) {
            if ($mimeType == $imgType[1]) {
                $checkTrue = true;
            }
        }
        if ($checkTrue !== true) {
            throw new \InvalidArgumentException("Mime type is blocked!");
        }

        $fileExt = $photoFile->getClientOriginalExtension();
        $checkTrue = false;
        foreach ($this->supportImageTypeList as $imgType) {
            if ($fileExt == $imgType[0]) {
                $checkTrue = true;
            }
        }

        if ($checkTrue == false) {
            throw new \InvalidArgumentException("Extension is blocked!");
        }

        return true;
    }
}