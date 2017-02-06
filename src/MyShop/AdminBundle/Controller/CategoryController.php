<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\DefaultBundle\Entity\Category;
use MyShop\DefaultBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class CategoryController extends Controller
{
    /**
     * @Route("/category/list/")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $categoryList = $this->getDoctrine()
            ->getManager()
            ->createQuery("SELECT cat_name FROM MyShopDefaultBundle:Category cat_name WHERE cat_name.parentCategory is null")
            ->getResult();

        return $this->render('MyShopAdminBundle:Category:list.html.twig', [
            "categoryList" => $categoryList
        ]);
    }

    /**
     * @Route("/category/add/{idParent}", defaults= {"idParent" = null}, requirements={"idParent":  "\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request, $idParent = null)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $manager = $this->getDoctrine()->getManager();

            if ($idParent !== null)
            {
                $parentCat = $this->getDoctrine()->getManager()->getRepository("MyShopDefaultBundle:Category")->find($idParent);
                $category->setParentCategory($parentCat);
            }

            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute("myshop_admin_category_list");
        }

        return $this->render('MyShopAdminBundle:Category:add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/edit/")
     * @return array
     */
    public function editAction()
    {

        return $this->render('MyShopAdminBundle:Category:edit.html.twig', [

        ]);
    }

    public function deleteAction()
    {

        return $this->render('MyShopAdminBundle:Category:edit.html.twig', [

        ]);
    }
}