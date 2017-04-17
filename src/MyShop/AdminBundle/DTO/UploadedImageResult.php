<?php

namespace MyShop\AdminBundle\DTO;


class UploadedImageResult
{
    private $originalFile;
    private $mobileFileName;
    private $mainFileName;
    private $thumbFileName;
    private $basketFileName;
    public function __construct($originalFile, Array $arrArgs)
    {
        $this->originalFile = $originalFile;
        $this->mobileFileName = $arrArgs["mobile"];
        $this->mainFileName = $arrArgs["main"];
        $this->thumbFileName = $arrArgs["thumb"];
        $this->basketFileName = $arrArgs["basket"];
    }

    /**
     * @return mixed
     */
    public function getMobileFileName()
    {
        return $this->mobileFileName;
    }

    /**
     * @return mixed
     */
    public function getMainFileName()
    {
        return $this->mainFileName;
    }

    /**
     * @return mixed
     */
    public function getThumbFileName()
    {
        return $this->thumbFileName;
    }

    /**
     * @return mixed
     */
    public function getBasketFileName()
    {
        return $this->basketFileName;
    }

    /**
     * @return mixed
     */
    public function getOriginalFile()
    {
        return $this->originalFile;
    }
}