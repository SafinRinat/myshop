<?php

namespace MyShop\AdminBundle\Controller;

use Doctrine\ORM\Query\Expr\Base;
use MyShop\DefaultBundle\Entity\Product;
use MyShop\DefaultBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MyShop\AdminBundle\Controller\BaseController;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends BaseController
{
    /**
     * @Route("/product/delete/{id}/", requirements={"id":"\d+"})
     * @Method({"GET", "POST"})
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $product = $this->getRepository("MyShopDefaultBundle:Product")->find($id);
        $manager = $this->getManager();
        $manager->remove($product);
        $this->get("session")->set("history",  $this->get("session")->get("history") . "<br> product delete");
        $manager->flush();
        $this->addFlash("success", "Product was removed from database");
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
        $category = $this->getRepository("MyShopDefaultBundle:Category")->find($id_category);
        $this->get("session")->set("history",  $this->get("session")->get("history") . "<br> product list by category");
        if (is_null($category)) {
            $productList = false;
        } else {
            $productList = $category->getProductList();
        }
        return $this->render("MyShopAdminBundle:Product:list.html.twig", [
            "productList" => $productList
        ]);
    }

    /**
     * @Route("/product/edit/{id}/", requirements={ "id": "\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $product = $this->getRepository("MyShopDefaultBundle:Product")->find($id);
        if ($product === null) {
            $this->addFlash("error", "Product not found!");
            return $this->redirectToRoute("myshop_admin_product_list");
        }
        $form = $this->createForm(ProductType::class, $product);

        /******************************************/
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())
            {
                $manager = $this->getManager();
                $manager->persist($product);
                $manager->flush();

                $this->addFlash("success", "Product id: ". $id ." was edit");
                $this->get("session")->set("history",  $this->get("session")->get("history") . "<br> product edit");
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $productList = $this->getManager()
            ->createQuery("select p, c from MyShopDefaultBundle:Product p join  p.category c")
            ->getResult();
        $this->get("session")->set("history", $this->get("session")->get("history") . "<br> product list");
        return $this->render("MyShopAdminBundle:Product:list.html.twig", [
            "productList" => $productList
        ]);
    }

    /**
     * @Route("/product/add/")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
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
                $manager = $this->getManager();
                $manager->persist($product);
                $manager->flush();

                // test notify to email
                $messege = new \Swift_Message();
                $messege->setTo("safinrinat87@gmail.com");
                $messege->setFrom("e.symfony@gmail.com");
                $messege->setBody("<b>Product add: </b>" . $product->getModel(), "text/html");
                $messege->attach(\Swift_Attachment::fromPath($this->get("kernel")->getRootDir() . "/../web/photos/22424547636.png"));
                $mailer = $this->get("mailer");
                $mailer->send($messege);

                $this->get("session")->set("history",  $this->get("session")->get("history") . "<br> product add");
                $this->addFlash("success", "Product id: ". $product->getId() ." was add");

                return $this->redirectToRoute("myshop_admin_product_list");
            }
        }
        /******************************************/
        return $this->render("MyShopAdminBundle:Product:add.html.twig", [
            "form" => $form->createView()
        ]);
    }
}