<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {


        return $this->render("@MyShopDefault/Default/index.html.twig", [
            "newsList" => "",
            "productList" => '...'
        ]);
    }

    public function createSomeProductAction()
    {
        $product = new Product();
        $product->setModel("J5");
        $product->setPrice(200);
        $product->setDescription("Great mobile phone");

        $doctine = $this->getDoctrine();
        $manager = $doctine->getManager();

        $manager->persist($product);
        $manager->flush();

        $response = new Response();
        $response->setContent($product->getId());
        return $response;
    }

    /**
     * @Template()
    */
    public function showProductAction(Request $request, $id)
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopDefaultBundle:Product");
        $product = $repository->find($id);

        return [
            "product" => $product
        ];
    }

    /**
     * @Template()
    */
    public function showProductListAction()
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShopDefaultBundle:Product");

        $productList = $repository->findAll();

        return [
            "productList" => $productList
        ];
    }
}
