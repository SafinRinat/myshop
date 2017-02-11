<?php

namespace MyShop\AdminBundle\ImageUtils;


class ImageNameGenerator
{
    public function generateName()
    {
        return rand(100, 999999999);
    }
}