<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Entity\Comment;
use App\Form\BurgerType;
use App\Form\CommentType;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BurgerController extends AbstractController
{
    #[Route('/burger', name: 'app_burger')]
    public function index(BurgerRepository $burgerRepository): Response
    {
        return $this->render('burger/index.html.twig', [
            'controller_name' => 'BurgerController',
            "burgers"=>$burgerRepository->findAll(),
        ]);
    }

    #[Route('/burger/show/{id}', name: 'app_show')]
    public function show(Burger $burger): Response

    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);


        return $this->render('burger/show.html.twig', [
            'controller_name' => 'BurgerController',
            "burger"=>$burger,
            "form"=>$form->createView(),

        ]);
    }

    #[Route('/burger/create', name: 'app_create')]
    public function create(Request $request, EntityManagerInterface $manager):Response
    {
        $burger= new Burger();
        $form = $this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($burger);
            $manager->flush();

            return $this->redirectToRoute("app_burger");
        }


        return $this->render('burger/create.html.twig', [
            'controller_name' => 'BurgerController',
            "form"=>$form->createView(),
            "btnValue"=>"Créer"
        ]);

    }

    #[Route('/burger/delete/{id}', name: 'app_delete')]
    public function delete(EntityManagerInterface $manager, Burger $burger):Response
    {
        $manager->remove($burger);
        $manager->flush();

        return $this->redirectToRoute("app_burger");



    }

    #[Route('/burger/edit/{id}', name: 'app_edit')]
    public function edit(Request $request, EntityManagerInterface $manager, Burger $burger):Response
    {
        $form = $this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($burger);
            $manager->flush();

            return $this->redirectToRoute("app_show", ["id"=>$burger->getId()]);
        }


        return $this->render('burger/create.html.twig', [
            'controller_name' => 'BurgerController',
            "form"=>$form->createView(),
            "btnValue"=>"Modifier"
        ]);

    }




}
