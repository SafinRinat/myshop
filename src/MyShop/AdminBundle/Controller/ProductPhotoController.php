<?php

namespace MyShop\AdminBundle\Controller;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Eventviva\ImageResize;
use MyShop\DefaultBundle\Entity\ProductPhoto;
use MyShop\DefaultBundle\Form\ProductPhotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ProductPhotoController extends BaseController
{
    /**
     * @Route("/photos/product/{id_product}/", requirements={"id_product": "\d+"})
     * @Method({"GET", "POST"})
     * @param $id_product
     */
    public function listAction($id_product)
    {
        $product = $this->getRepository("MyShopDefaultBundle:Product")->find($id_product);
        return $this->render("MyShopAdminBundle:ProductPhoto:list.html.twig", [
                "product" => $product
        ]);
    }

    /**
     * @Route("/photo/add/product/{id_product}/", requirements={"id_product","\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param $id_product
     */
    public function addPhotoAction(Request $request, $id_product)
    {
        $product = $this->getRepository("MyShopDefaultBundle:Product")->find($id_product);
        if ($product == null) {
            return $this->createNotFoundException("Product not found!");
        }
        $photo = new ProductPhoto();
        $form = $this->createForm(ProductPhotoType::class, $photo);
        if ($request->isMethod("POST")) {
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
            $result = $this->get("myshop_admin.upload_image_service")->uploadImage($photoFile, $id_product);
            $photo->setFileName($result->getOriginalFile());
            $photo->setMobileFileName($result->getMobileFileName());
            $photo->setMainFileName($result->getMainFileName());
            $photo->setThumbFileName($result->getThumbFileName());
            $photo->setBasketFileName($result->getBasketFileName());
            $photo->setProduct($product);
            $manager = $this->getManager();
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
    public function deletePhotoAction($id_product, $id_photo)
    {
        $productPhoto = $this
            ->getRepository("MyShopDefaultBundle:ProductPhoto")
            ->find($id_photo);
        $removeFiles = $this->get("myshop_admin.remove_files");
        $removeFiles->setPhotoArray($productPhoto->getUnlinkNames());
        $removeFiles->removeFiles();
        $manager = $this->getManager();
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

        $product = $this->getRepository("MyShopDefaultBundle:Product")->find($id_product);
        if ($product == null) {
            return $this->createNotFoundException("Product not found!");
        }

        $photo = $this
            ->getRepository("MyShopDefaultBundle:ProductPhoto")
            ->find($id_photo);
        $form = $this->createForm(ProductPhotoType::class, $photo);
        $photoArray = $photo->getUnlinkNames();;
        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if ($form->isSubmitted())
            {
                $filesArr = $request->files->get("myshop_defaultbundle_productphoto");
                /** @var UploadedFile $photoFile */
                $photoFile = $filesArr["photoFile"];

                $checkImgService = $this->get("myshop_admin.check_img");

                try {
                    $checkImgService->check($photoFile);
                } catch (\InvalidArgumentException $ex) {
                    die("Image type error!");
                }
                $result = $this->get("myshop_admin.upload_image_service")->uploadImage($photoFile, $id_product);
                $photo->setFileName($result->getOriginalFile());
                $photo->setMobileFileName($result->getMobileFileName());
                $photo->setMainFileName($result->getMainFileName());
                $photo->setThumbFileName($result->getThumbFileName());
                $photo->setBasketFileName($result->getBasketFileName());
                $photo->setProduct($product);
                $manager = $this->getManager();
                $manager->persist($photo);
                $manager->flush();
                $removeFiles = $this->get("myshop_admin.remove_files");
                $removeFiles->setPhotoArray($photoArray);
                $removeFiles->removeFiles();
                return $this->redirectToRoute("myshop_admin_productphoto_list", ['id_product' => $id_product]);
            }
        }

        return $this->render("MyShopAdminBundle:ProductPhoto:edit.html.twig", [
            "form" => $form->createView(),
            "photo" => $photo,
            'product'=> $product
        ]);
    }


    public function testfileAction(Request $request)
    {
        $dir = $this->get("kernel")->getRootDir() . "/../src/MyShop/DefaultBundle/DataFixtures/Files";
        $files = scandir($dir);

        $dirTo = $this->get("kernel")->getRootDir() . "/../web/photos/";

        $manager = $this->getDoctrine()->getManager();

        foreach ($files as $file)
        {
            if ($file !== "." && $file !== "..")
            {
                $fileFullPath =  $dir . "/" . $file;
                $fileFullPath = realpath($fileFullPath);

                copy($fileFullPath, $dirTo . $file);

                $product = new Product();
                $product->setModel(rand());
                $product->setPrice(rand());
                $product->setDescription(rand());
                $product->setIconFileName($file);

                $manager->persist($product);
                $manager->flush();
            }
        }

//        $files = $request->files; // $_FILES
//
//        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $myFile */
//        $myFile = $files->get("test_file");
//
//        $appDir = $this->get("kernel")->getRootDir() . "/../web/";
//        $myFile->move($appDir, $myFile->getClientOriginalName());

        return [];
    }
}

/***
/Users/igor/projects/school/myshop/src/MyShop/AdminBundle/Controller/ProductController.php:189:
object(Symfony\Component\HttpFoundation\FileBag)[14]
protected 'parameters' =>
array (size=1)
'test_file' =>
object(Symfony\Component\HttpFoundation\File\UploadedFile)[15]
private 'test' => boolean false
private 'originalName' => string 'Screen Shot 2017-03-15 at 19.10.01.png' (length=38)
private 'mimeType' => string 'image/png' (length=9)
private 'size' => int 48433
private 'error' => int 0
private 'pathName' (SplFileInfo) => string '/private/var/folders/9b/q3dspccj2zd15dpv8048_rg80000gn/T/php5GXGh0' (length=66)
private 'fileName' (SplFileInfo) => string 'php5GXGh0' (length=9)**/