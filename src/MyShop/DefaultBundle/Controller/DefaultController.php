<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('DefaultBundle:Default:index.html.twig');
    }

    /**
     * @Route("create/some/")
     */
    public function createSomeProductAction()
    {
        $product = new Product();
        $product->setModel("iPhone");
        $product->setPrice(200);
        $product->setShortDescription("Great mobile phone");

        $manager = $this->getDoctrine()->getManager();

        $manager->persist($product);
        $manager->flush();

        $response = new Response();

        $response->setContent($product->getId());
    }

}
