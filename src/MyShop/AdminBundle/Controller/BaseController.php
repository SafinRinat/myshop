<?php

namespace MyShop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    protected function getManager()
    {
        return $this->getDoctrine()->getManager();
    }

    protected function getRepository($repositoryName)
    {
        return $this->getDoctrine()->getManager()->getRepository($repositoryName);
    }
}