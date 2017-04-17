<?php

namespace MyShop\AdminBundle\ImageUtils;


use Eventviva\ImageResize;
use MyShop\AdminBundle\DTO\UploadedImageResult;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadImageService
{
    /**
     * @var CheckImg
    */
    private $checkImg;
    /**
     * @var ImageNameGenerator
    */
    private $imageNameGenerator;
    private $uploadImageRootDir;
    private $listSizeUploadImage;

    public function __construct(CheckImg $checkImg,
                                ImageNameGenerator $imageNameGenerator,
                                $listSizeUploadImage)
    {
        $this->checkImg = $checkImg;
        $this->imageNameGenerator = $imageNameGenerator;
        $this->listSizeUploadImage = $listSizeUploadImage;
    }

    public function setUploadImageRootDir($imageRootDir)
    {
        $this->uploadImageRootDir = $imageRootDir;
    }

    public function getUploadImageRootDir()
    {
        return $this->uploadImageRootDir;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param $product_id
     * @return UploadedImageResult $result
     * @throws \Exception
     */
    public function uploadImage(UploadedFile $uploadedFile, $product_id)
    {
        $originalFile = $product_id .
                        $this->imageNameGenerator->generateName() . "." .
                        $uploadedFile->getClientOriginalExtension();
        try {
            $uploadedFile->move($this->uploadImageRootDir, $originalFile);
        } catch (\Exception $exception) {
            echo "Can not move file - upload_max_filesize > limit = 2048kb!";
            throw $exception;
        }
        $arrArgs = [];
        foreach ($this->listSizeUploadImage as $key => $value) {
            $imgResize = new ImageResize($this->uploadImageRootDir . $originalFile);
            $imgResize->resize($value["width"], $value["height"]);
            $arrArgs[$key] = $key . "_" . $originalFile;
            $imgResize->save($this->uploadImageRootDir . $arrArgs[$key]);
        }
        $result = new UploadedImageResult($originalFile, $arrArgs);
        return $result;
    }
}