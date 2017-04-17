<?php

namespace MyShop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    protected function getManager()
    {
        return $manager = $this
            ->getDoctrine()
            ->getManager();
    }

    protected function getRepository($repositoryName)
    {
        return $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository($repositoryName);
    }
}