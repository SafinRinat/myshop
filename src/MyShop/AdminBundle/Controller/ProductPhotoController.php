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
     * @Route("/product/{idProduct}/photos/", requirements={"idProduct": "\d+"})
     * @Method({"GET", "POST"})
     * @param $idProduct
     * @return array
     */
    public function listAction($idProduct)
    {
        $product = $this->getDoctrine()
            ->getManager()
            ->getRepository("MyShopDefaultBundle:Product")
            ->find($idProduct);

        return $this->render("MyShopAdminBundle:ProductPhoto:list.html.twig", [
                "product" => $product
        ]);
    }

    /**
     * @Route("/product/{idProduct}/photo/add/", requirements={"idProduct","\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param $idProduct
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function addAction(Request $request, $idProduct)
    {
        $product = $this->getDoctrine()
            ->getManager()
            ->getRepository("MyShopDefaultBundle:Product")
            ->find($idProduct);

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

            $imageNameGenerator = $this->get("myshop_admin.image_name_generator");

            $photoFileName = $product->getId() . $imageNameGenerator->generateName() . "." . $photoFile->getClientOriginalExtension();
            $photoDirPath = $this->get("kernel")->getRootDir() . "/../web/photos/";

            $photoFile->move($photoDirPath, $photoFileName);

            $img = new ImageResize($photoDirPath . $photoFileName);
            $img->resizeToBestFit(250, 200);
            $smallPhotoName = "small_" . $photoFileName;
            $img->save($photoDirPath . $smallPhotoName);

            $photo->setSmallFileName($smallPhotoName);
            $photo->setFileName($photoFileName);
            $photo->setProduct($product);

            $product->persist($photo);
            $product->flush();
        }

        return $this->render("MyShopAdminBundle:ProductPhoto:add.html.twig". [
                "form" => $form->createView(),
                "product" => $product
        ]);
    }
}