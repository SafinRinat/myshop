<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\AdminBundle\Utils\JSTreeCategoryService;
use MyShop\DefaultBundle\Entity\Category;
use MyShop\DefaultBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class CategoryController extends BaseController
{
    /**
     * @Route("/category/tree")
     */
    public function treeAction()
    {
        $jsTreeService = $this->get("myshop_admin.jstree");//$categoryList = $this->getRepository("MyShopDefaultBundle:Category")->findAll();
        $dataJson = $jsTreeService->getCategoryListJSON();
        return $this->render("MyShopAdminBundle:Category:tree.html.twig", [
            "categoryListJson" => $dataJson
        ]);
    }

    /**
     * @Route("/category/list/{id_parent}", defaults={"id_parent" : null}, requirements={"id_parent" : "\d+"})
     * @param $id_parent
     * @var integer
     * @return Response
     */
    public function listAction($id_parent = null)
    {
        $manager = $this->getManager();
        $viewData = [];

        if (is_null($id_parent)) {
            $viewData["categoryList"] = $manager
                ->createQuery("SELECT cat_name FROM MyShopDefaultBundle:Category cat_name WHERE cat_name.parentCategory is null")
                ->getResult();
        } else {
            $parentCategory = $manager->getRepository("MyShopDefaultBundle:Category")->find($id_parent);
            $viewData["categoryParent"] = $parentCategory;
            $viewData["categoryList"] = $parentCategory->getChildrenCategories();
        }
        return $this->render('MyShopAdminBundle:Category:list.html.twig', $viewData);
    }

    /**
     * @Route("/category/add/{id_parent}", defaults={"id_parent" : null}, requirements={"id_parent" : "\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param $id_parent
     * @var Request
     * @var Integer
     * @return Response
     */
    public function addAction(Request $request, $id_parent = null)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $manager = $this->getManager();

            if (!is_null($id_parent))
            {
                $parentCat = $this->getRepository("MyShopDefaultBundle:Category")->find($id_parent);
                $category->setParentCategory($parentCat);
            }

            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute("myshop_admin_category_list", [
                "id_parent" => $id_parent
            ]);
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
     * @return Response
     */
    public function editAction(Request $request, $id_category)
    {
        $category = $this->getRepository("MyShopDefaultBundle:Category")->find($id_category);

        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())
            {
                $manager = $this->getManager();
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
        $deletedCategory = $this->getRepository("MyShopDefaultBundle:Category")->find($id_category);
        $manager = $this->getManager();
//        $deletedCategory->removeChildrenCategory($deletedCategory->getChildrenCategories());
        if(count($deletedCategory->getChildrenCategories())){
            $deletedCategory->getChildrenCategories()->forAll(function ($item) {
                dump($item);
            });
        } else {
            $manager->remove($deletedCategory);
            $manager->flush();
        }

        die();
        return $this->redirectToRoute("myshop_admin_category_list");
    }

    /**
     * @Route("/category/deleteAjax/{id_category}", requirements={"id_category" : "\d+"})
     * @param $id_category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAjaxAction($id_category)
    {
        $category =  $this->getRepository("MyShopDefaultBundle:Category")->find($id_category);
        $msg  = [];
        if (is_null($category)){
            $msg[] = ["status" => "category not found"];
        } else {
            $count = count($category->getChildrenCategories());
            if ($count !== 0) {
                try {
                    $category->removeChildrenCategory($category->getChildrenCategories());
                    $this->deleteAction($category);
                    $msg[] = [
                        "status" => "success delete",
                        "category name" => $category->getName()
                    ];
                } catch (\InvalidArgumentException $exception) {
                    $msg[] = [
                        "status" => "error remove",
                        "category id" => $id_category
                    ];
                }
            } else {
                try {
                    $this->deleteAction($category);
                    $msg[] = [
                        "status" => "success delete",
                        "category name" => $category->getName()
                    ];
                } catch (\InvalidArgumentException $exception) {
                    $msg[] = [
                        "status" => "error remove",
                        "category id" => $id_category
                    ];
                }
            }
        }
        /** @var TYPE_NAME $responseJson */
        $responseJson = new JsonResponse(array($msg));
        return $responseJson;
    }
}