<?php
/**
 * Created by PhpStorm.
 * User: rinat
 * Date: 3/26/17
 * Time: 02:17
 */

namespace MyShop\AdminBundle\ImageUtils;


class RemoveFiles
{
    protected $photoArray = [];
    public function __construct($photoArray = null)
    {
        $this->photoArray = $photoArray;
    }


}