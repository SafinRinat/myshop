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
     * @Route("/category/edit/{id_category}", requirements={"id_category":"\d+"})
     * @param Request $request
     * @param $id_category
     * @return rendered template
     */
    public function editAction(Request $request, $id_category)
    {
        $category = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Category")->find($id_category);

        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())
            {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($category);
                $manager->flush();

                return $this->redirectToRoute("myshop_admin_category_list");
            }
        }

        return $this->render('MyShopAdminBundle:Category:edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/category/delete/{id_category}", requirements={"id_category":"\d+"})
     * @Method({"GET", "POST"})
     * @param $id_category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id_category)
    {
        $deletedCategory = $this
            ->getDoctrine()
            ->getRepository("MyShopDefaultBundle:Category")
            ->find($id_category);
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($deletedCategory);
        $manager->flush();

        return $this->redirectToRoute("myshop_admin_category_list");
    }
}