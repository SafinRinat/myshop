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

    //почему UploadedFile ?
    public function check(UploadedFile $photoFile)
    {
        $checkTrue = false;
        $mimeType = $photoFile->getClientMimeType();
        $fileExt = $photoFile->getClientOriginalExtension();

        $mimeType = strtolower($mimeType);

        foreach ($this->supportImageTypeList as $imgType) {
            if ($mimeType === $imgType[1]) {
                $checkTrue = true;
            }
        }

        if ($checkTrue !== true) {
//            $this->addFlash("error", "Mime type is blocked!");
            throw new \InvalidArgumentException("Mime type is blocked!");
        }

        $checkTrue = false;
        foreach ($this->supportImageTypeList as $imgType) {
            if ($fileExt === $imgType[0]) {
                $checkTrue = true;
            }
        }

        if ($checkTrue == false) {
//            $this->addFlash("error", "Extension is blocked!");
            throw new \InvalidArgumentException("Extension is blocked!");
        }

        return true;
    }
}