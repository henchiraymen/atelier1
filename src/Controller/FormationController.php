<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('formation')]
class FormationController extends AbstractController
{
    #[Route('/', name: 'app_formation')]
    public function index(): Response
    {
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
        ]);
    }
    #[Route('/test/{nom}')]
    function test($nom){
        #return new Response("Bonjour <b>".$nom."</b>");
        return $this->render("formation/test.html.twig",['n'=>$nom]);
    }
    #[Route('/list')]
    function list(){
        $authors = array(
            array('id' => 1, 'image' => '/images/img1.png','username' => 'Victor   Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'image' => '/images/img1.png','username' => ' William Shakespeare', 'email' => ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'image' => '/images/img1.png','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        return $this->render("formation/list.html.twig",['authors'=>$authors]);
    }
    #[Route('/detail/{i}', name:'d')]
    function detail($i){
        return $this->render('formation/detail.html.twig',['i'=>$i]);

    }
}
