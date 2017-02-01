<?php
namespace MyShop\AdminBundle\Controller;

use MyShop\DefaultBundle\Entity\Category;
use MyShop\DefaultBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\Container;

class CategoryController extends Container
{
    /**
     * @Route("list/")
     */
    public function listAction()
    {
        $categoryList = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Category")->findAll();
    }
}