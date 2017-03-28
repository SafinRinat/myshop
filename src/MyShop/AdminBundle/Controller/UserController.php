<?php

namespace MyShop\AdminBundle\Controller;

use MyShop\AdminBundle\Entity\User;
use MyShop\AdminBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Template()
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

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute("my_shop_admin.index");
        }

        return [
            'form' => $form->createView()
        ];
    }
}
