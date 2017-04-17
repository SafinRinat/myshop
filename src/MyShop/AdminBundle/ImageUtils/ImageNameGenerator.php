<?php

namespace MyShop\AdminBundle\ImageUtils;


class ImageNameGenerator
{
    public function generateName()
    {
        return rand(100000, 9999999999);
    }
}