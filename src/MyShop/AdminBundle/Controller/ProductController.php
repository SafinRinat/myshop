<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    /**
     * @Route("/product/delete/{id}/", requirements={"id":"\d+"})
     * @Method({"GET", "POST"})
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $product = $this
                        ->getDoctrine()
                        ->getRepository("MyShopDefaultBundle:Product")
                        ->find($id);
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($product);
        $manager->flush();

        return $this->redirectToRoute("myshop_admin_product_list");
    }


    /**
     * @Route("/category/product/list/{id_category}/", requirements={"id_category": "\d+"})
     * @Method({"GET", "POST"})
     * @param $id_category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listByCategoryAction($id_category)
    {
        $category = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Category")->find($id_category);
        $productList = $category->getProductList();

        return $this->render("MyShopAdminBundle:Product:list.html.twig", [
            "productList" => $productList
        ]);
    }

    /**
     * @Route("/product/edit/{id}/", requirements={ "id": "\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param $id
     */
    public function editAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($id);

        $form = $this->createForm(ProductType::class, $product);

        /******************************************/
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())
            {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($product);
                $manager->flush();

                return $this->redirectToRoute("myshop_admin_product_list");
            }
        }
        /******************************************/
        return $this->render("MyShopAdminBundle:Product:edit.html.twig", [
            "form" => $form->createView(),
            "product" => $product
        ]);
    }

    /**
     * @Route("/product/list/")
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $productList = $this->getDoctrine()
            ->getManager()
            ->createQuery("select p, c from MyShopDefaultBundle:Product p join p.category c")
            ->getResult();

        return $this->render("MyShopAdminBundle:Product:list.html.twig", [
            "productList" => $productList
        ]);
    }

    /**
     * @Route("/product/add/")
     * @param Request $request
     */
    public function addAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        /******************************************/
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())
            {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($product);
                $manager->flush();

                return $this->redirectToRoute("myshop_admin_product_list");
            }
        }
        /******************************************/
        return $this->render("MyShopAdminBundle:Product:add.html.twig", [
            "form" => $form->createView()
        ]);
    }
}