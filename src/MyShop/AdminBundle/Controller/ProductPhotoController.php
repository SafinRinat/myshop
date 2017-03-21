<?php

namespace MyShop\AdminBundle\Controller;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Eventviva\ImageResize;
use MyShop\DefaultBundle\Entity\ProductPhoto;
use MyShop\DefaultBundle\Form\ProductPhotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ProductPhotoController extends Controller
{
    /**
     * @Route("/photos/product/{id_product}/", requirements={"id_product": "\d+"})
     * @Method({"GET", "POST"})
     * @param $id_product
     * @return array
     *
     */
    public function listAction($id_product)
    {
        $product = $this->getDoctrine()
            ->getManager()
            ->getRepository("MyShopDefaultBundle:Product")
            ->find($id_product);

        return $this->render("MyShopAdminBundle:ProductPhoto:list.html.twig", [
                "product" => $product
        ]);
    }

    /**
     * @Route("/photo/add/product/{id_product}/", requirements={"id_product","\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param $id_product
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     */
    public function addPhotoAction(Request $request, $id_product)
    {
        $manager = $this->getDoctrine()->getManager();
        $product = $manager->getRepository("MyShopDefaultBundle:Product")->find($id_product);
        if ($product == null) {
            return $this->createNotFoundException("Product not found!");
        }

        $photo = new ProductPhoto();
        $form = $this->createForm(ProductPhotoType::class, $photo);


        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $filesAr = $request->files->get("myshop_defaultbundle_productphoto");

            /** @var UploadedFile $photoFile */
            $photoFile = $filesAr["photoFile"];

            $checkImgService = $this->get("myshop_admin.check_img");

            try {
                $checkImgService->check($photoFile);
            } catch (\InvalidArgumentException $ex) {
                die("Image type error!");
            }

            $result = $this->get("myshop_admin.image_uploader")->uploadImage($photoFile, $id_product);

            $photo->setSmallFileName($result->getSmallFileName());
            $photo->setFileName($result->getBigFileName());
            $photo->setProduct($product);

            $manager->persist($photo);
            $manager->flush();

            return $this->redirectToRoute("myshop_admin_productphoto_list", ['id_product' => $id_product]);
        }

        return $this->render("MyShopAdminBundle:ProductPhoto:add.html.twig", [
                "form" => $form->createView(),
                "product" => $product
        ]);
    }

    /**
     * @Route("/product/{id_product}/photo/delete/{id_photo}", requirements={"id_product": "\d+", "id_photo": "\d+"})
     * @param $id_photo
     *
     */
    public function deletePhotoAction($id_product, $id_photo) {

        $product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($id_product);

        $productPhoto = $this
            ->getDoctrine()
            ->getRepository("MyShopDefaultBundle:ProductPhoto")
            ->find($id_photo);

        //TODO: make method on service delet foto on upload folder
        //small_file_name -> photo
        $getSmallFileName = $productPhoto->getSmallFileName();
        //file_name -> photo
        $getFileName = $productPhoto->getFileName();

        $manager = $this->getDoctrine()->getManager();

        $manager->remove($productPhoto);
        $manager->flush();

        return $this->redirectToRoute("myshop_admin_productphoto_list", ['id_product' => $id_product]);
    }

    /**
     * @Route("/product/{id_product}/photo/edit/{id_photo}/", requirements={"id_product": "\d+", "id_photo": "\d+"})
     *
     */
    public function editPhotoAction(Request $request, $id_product, $id_photo)
    {

        $product = $this->getDoctrine()->getRepository("MyShopDefaultBundle:Product")->find($id_product);

        if ($product == null) {
            return $this->createNotFoundException("Product not found!");
        }

        $photo = $this->getDoctrine()->getRepository("MyShopDefaultBundle:ProductPhoto")->find($id_photo);

        $form = $this->createForm(ProductPhotoType::class, $photo);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())
            {
                $filesAr = $request->files->get("myshop_defaultbundle_productphoto");
                /** @var UploadedFile $photoFile */
                $photoFile = $filesAr["photoFile"];

                $checkImgService = $this->get("myshop_admin.check_img");

                try {
                    $checkImgService->check($photoFile);
                } catch (\InvalidArgumentException $ex) {
                    die("Image type error!");
                }
                $result = $this->get("myshop_admin.image_uploader")->uploadImage($photoFile, $id_product);

                $photo->setSmallFileName($result->getSmallFileName());
                $photo->setFileName($result->getBigFileName());
                $photo->setProduct($product);

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($photo);
                $manager->flush();

                return $this->redirectToRoute("myshop_admin_productphoto_list", ['id_product' => $id_product]);
            }
        }

        return $this->render("MyShopAdminBundle:ProductPhoto:edit.html.twig", [
            "form" => $form->createView(),
            "photo" => $photo,
            'product'=> $product
        ]);
    }
}