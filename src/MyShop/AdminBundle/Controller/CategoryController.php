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
     * @Route("/category/tree")
     */
    public function treeAction()
    {
        $categoryList = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Category")->findAll();
        $results = [];
        /** @var Category $cat */
        foreach ($categoryList as $cat)
        {
            if ($cat->getParentCategory() !== null) {
                $id_parent = $cat->getParentCategory()->getId();
            } else {
                $id_parent = "#";
            }
            $results[] = [
                "id" => $cat->getId(),
                "parent" => $id_parent,
                "text" => $cat->getName() . " <a href='#'>[X]</a><a href='#'>[E]</a>"
            ];
        }

        $dataJson = json_encode($results);
        return $this->render("MyShopAdminBundle:Category:tree.html.twig", [
            "categoryListJson" => $dataJson
        ]);
    }

    /**
     * @Route("/category/list/{id_parent}", defaults={"id_parent" : null}, requirements={"id_parent" : "\d+"})
     */
    public function listAction($id_parent = null)
    {
        $manager = $this->getDoctrine()->getManager();
        $viewData = [];

        if (is_null($id_parent)) {
            $viewData["categoryList"] = $manager
                ->createQuery("SELECT cat_name FROM MyShopDefaultBundle:Category cat_name where cat_name.parentCategory is null")
                ->getResult();
        } else {
            $parentCategory = $manager->getRepository("MyShopDefaultBundle:Category")->find($id_parent);
            $viewData["categoryList"] = $parentCategory->getChildrenCategories();
            $viewData["categoryParent"] = $parentCategory;
        }
        return $this->render('MyShopAdminBundle:Category:list.html.twig', $viewData);
    }

    /**
     * @Route("/category/add/{id_parent}", defaults={"id_parent" : null}, requirements={"id_parent" : "\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     */
    public function addAction(Request $request, $id_parent = null)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $manager = $this->getDoctrine()->getManager();

            if ($id_parent !== null)
            {
                $parentCat = $this->getDoctrine()->getManager()->getRepository("MyShopDefaultBundle:Category")->find($id_parent);
                $category->setParentCategory($parentCat);
            }

            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute("myshop_admin_category_list");
        }

        return $this->render('MyShopAdminBundle:Category:add.html.twig', [
            "form" => $form->createView(),
            "id_parent" => $id_parent
        ]);
    }

    /**
     * @Route("/category/edit/{id_category}", requirements={"id_category" : "\d+"})
     * @param Request $request
     * @param $id_category
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
     * @Route("/category/delete/{id_category}", requirements={"id_category" : "\d+"})
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