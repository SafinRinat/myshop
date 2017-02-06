<?php

namespace MyShop\DefaultBundle\Controller;

use MyShop\DefaultBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('MyShopDefaultBundle:Default:index.html.twig');
    }

    /**
     * @Route("product/{id}", requirements={ "id": "\d+"})
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showProductAction($id)
    {
        $repository = $this
                        ->getDoctrine()
                        ->getManager()
                        ->getRepository("MyShopDefaultBundle:Product");

        $product = $repository->find($id);

        return $this->render('MyShopDefaultBundle:Default:showProduct.html.twig', [
            "product" => $product
        ]);
    }

    /**
     * @Route("create/some")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createSomeProductAction()
    {
        $product = new Product();
        $product->setModel("temp_model" . uniqid());
        $product->setPrice(rand(10,1000));
        $product->setDescription("Great temp product");

        $doctine = $this->getDoctrine()->getManager();

        $doctine->persist($product);
        $doctine->flush();

        return $this->render('MyShopDefaultBundle:Default:createSome.html.twig', [
            "productId" => $product->getId()
        ]);
    }

    /**
     * @Route("product/list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showProductListAction()
    {
        $repository = $this
                        ->getDoctrine()
                        ->getManager()
                        ->getRepository("MyShopDefaultBundle:Product");

        $productList = $repository->findAll();

        return $this->render('MyShopDefaultBundle:Default:showProductList.html.twig', [
            "productList" => $productList
        ]);
    }

}
