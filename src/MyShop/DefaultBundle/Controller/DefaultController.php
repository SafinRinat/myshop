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
     * @Route("/create/some/")
     * @return Response
     */
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
     * @Route(
     *     "product/show/{id}",
     *     requirements = {
     *     "id": "\d+"
     * }
     *     ),
     * @param Request $request
     * @return array
     */
    public function showProductAction(Request $request, $id)
    {
        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $repository = $manager->getRepository("MyShop:DefaultBundle:Product");
        $product = $repository->find($id);

        return [
          "product" => $product
        ];
    }

    /**
     * @Route("product/show/all")
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
