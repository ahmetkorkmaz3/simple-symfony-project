<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserAddType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {

        $repository = $this->getDoctrine()->getRepository(User::class);

        $users = $repository->findAll();

        return $this->render('index/index.html.twig', [
            'title' => 'Home Page',
            'users' => $users,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/new", name="new_user")
     */
    public function new_user(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserAddType::class, $user);
        $form->add('submit', SubmitType::class, [
            'label' => 'User Create',
            'attr' => ['class' => 'btn btn-primary']
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $new_user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($new_user);
            $entityManager->flush();

            return $this->redirect('/');

        }


        return $this->render('index/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
