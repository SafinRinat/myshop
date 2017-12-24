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
     * @Route("/test/{photoId}/", requirements={"photoId": "\d+"})
     * @Method({"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function testAction($photoId) {
        $photo = $this->getRepository("MyShopDefaultBundle:ProductPhoto")->find($photoId);
        $productId = $photo->getProduct()->getId();
        $sendMail = $this->get("myshop_admin.send_email");
        $sendMail->setRecipientName("Rinat Safin");
        $sendMail->setRecipientEmail("safinrinat87@gmail.com");
        $sendMail->setMessage("АХАХА ЭТО Я");
        $sendMail->sendNotifyEmail("ACTION");
//        $sendMail->sendMail("test action");
        $this->addFlash("success", "test email has been send!");
        return $this->redirectToRoute("myshop_admin_productphoto_list", [
            "productId" => $productId
        ]);
//        $sendMail->sendNotifyEmail();
//        $sendMail->sendMail("testAction");
    }
    /**
     * @Route("/photos/product/{photoId}/sendmail", requirements={"photoId": "\d+"})
     * @Method({"GET", "POST"})
     * @param $photoId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendToMailAction($photoId)
    {
        //        don't work extend BaseController ->find  and $photo->getFileName();
        //        $photo = $this->getRepository("MyShopDefaultBundle:ProductPhoto");
        $photo = $this->getRepository("MyShopDefaultBundle:ProductPhoto")->find($photoId);
        $photoFile = $this->get("kernel")->getRootDir() . "/../web/photos/" . $photo->getFileName();
        $productId = $photo->getProduct()->getId();

        $messege = new \Swift_Message();
        $messege->setTo("safinrinat87@gmail.com");
        $messege->setFrom("e.symfony@gmail.com");
        //template html email $view
        //https://youtu.be/aEF2O1FhAmw?t=2733
//        $htmlResult = $this->renderView("MyShopAdminBundle::result.html.twig", [
//            "name" => "User",
//        ]);
        $messege->setBody("Take are photo", "text/html");
        $messege->attach(\Swift_Attachment::fromPath($photoFile));
        $mailer = $this->get("mailer");
        $mailer->send($messege);
        $this->addFlash("success", "Photo sent to email!");
        return $this->redirectToRoute("myshop_admin_productphoto_list", [
            "productId" => $productId
        ]);
    }

    /**
     *  @Route("/photos/product/{productId}/", requirements={"productId": "\d+"})
     * @Method({"GET", "POST"})
     * @param $productId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($productId)
    {
        $product = $this->getRepository("MyShopDefaultBundle:Product")->find($productId);
        return $this->render("MyShopAdminBundle:ProductPhoto:list.html.twig", [
                "product" => $product
        ]);
    }

    /**
     * @Route("/photo/add/product/{productId}/", requirements={"productId","\d+"})
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param $productId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addPhotoAction(Request $request, $productId)
    {
        $product = $this->getRepository("MyShopDefaultBundle:Product")->find($productId);
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
                $this->addFlash("error", "Image type error!");
                return $this->redirectToRoute("myshop_admin_productphoto_addphoto", ['productId' => $productId]);
            }
            $result = $this->get("myshop_admin.upload_image_service")
                ->uploadImage($photoFile, $productId);
            $photo->setFileName($result->getOriginalFile());
            $photo->setMobileFileName($result->getMobileFileName());
            $photo->setMainFileName($result->getMainFileName());
            $photo->setThumbFileName($result->getThumbFileName());
            $photo->setBasketFileName($result->getBasketFileName());
            $photo->setProduct($product);
            $manager = $this->getManager();
            $manager->persist($photo);
            $manager->flush();
            return $this->redirectToRoute("myshop_admin_productphoto_list", ['productId' => $productId]);
        }
        return $this->render("MyShopAdminBundle:ProductPhoto:add.html.twig", [
                "form" => $form->createView(),
                "product" => $product
        ]);
    }

    /**
     * @Route("/product/{productId}/photo/delete/{photoId}", requirements={"productId": "\d+", "photoId": "\d+"})
     * @param $productId
     * @param $photoId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletePhotoAction($productId, $photoId)
    {
        $productPhoto = $this
            ->getRepository("MyShopDefaultBundle:ProductPhoto")
            ->find($photoId);
        $removeFiles = $this->get("myshop_admin.remove_files");
//        $removeFiles->setPhotoArray();
        $removeFiles->removeFiles($productPhoto->getUnlinkNames());
        $manager = $this->getManager();
        $manager->remove($productPhoto);
        $manager->flush();
        return $this->redirectToRoute("myshop_admin_productphoto_list", [
            'productId' => $productId
        ]);
    }

    /**
     * @Route("/product/{productId}/photo/edit/{photoId}/", requirements={"productId": "\d+", "photoId": "\d+"})
     * @param $productId
     * @param $photoId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editPhotoAction(Request $request, $productId, $photoId)
    {

        $product = $this->getRepository("MyShopDefaultBundle:Product")->find($productId);

        if ($product == null) {
            $this->addFlash("error", "Product not found!");
            return $this->redirectToRoute("myshop_admin_product_list");
        }

        $photo = $this->getRepository("MyShopDefaultBundle:ProductPhoto")->find($photoId);
        $form = $this->createForm(ProductPhotoType::class, $photo);

        $photoArray = $photo->getUnlinkNames();

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $filesArr = $request->files->get("myshop_defaultbundle_productphoto");
                /** @var UploadedFile $photoFile */
                $photoFile = $filesArr["photoFile"];

                $checkImgService = $this->get("myshop_admin.check_img");

                try {
                    $checkImgService->check($photoFile);
                } catch (\InvalidArgumentException $ex) {
                    $this->addFlash("error", "Image type error!");
                    return $this->redirectToRoute("myshop_admin_productphoto_editphoto", ["productId" => $productId, 'photoId' => $photoId]);
                }

                $result = $this->get("myshop_admin.upload_image_service")->uploadImage($photoFile, $productId);
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
                $removeFiles->removeFiles($photoArray);
                return $this->redirectToRoute("myshop_admin_productphoto_list", [
                    'productId' => $productId
                ]);
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

        $manager = $this->getManager();

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

        return $this->render("MyShopAdminBundle:ProductPhoto:list.html.twig", [

        ]);
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