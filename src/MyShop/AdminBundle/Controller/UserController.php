<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\AdminBundle\Entity\User;
use MyShop\AdminBundle\Form\UserType;
use MyShop\AdminBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    /**
     * @Route("/user/add")
    */
    public function addAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        if ($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            $plainPassword = $user->getPlainPassword();
            $user->setPlainPassword("");
            $password = $this->get("security.password_encoder")->encodePassword($user, $plainPassword);
            $user->setPassword($password);

            $manager = $this->getManager();
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("myshop_admin_default_index");
        }

        return $this->render("@MyShopAdmin/User/add.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
