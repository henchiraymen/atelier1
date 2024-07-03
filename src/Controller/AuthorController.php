<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


#[Route('/author')]
class AuthorController extends AbstractController
{
    #[Route('/', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/affiche',name:'Auth_Aff')]
    function Affiche( AuthorRepository $repo){
        $authors = $repo->findAll();                                         
        return $this->render('author/affiche.html.twig',['authors'=>$authors]);                       
    }

    #[Route('/ajout')]
    function Ajout(Request $req,ManagerRegistry $manager){
        //Form
        $author=new Author();
        $form=$this->createForm(AuthorType::class,$author);
        $form->add('Ajout',SubmitType::class);
        $form->handleRequest($req);
        //Add
        if($form->isSubmitted() && $form->isValid()){
            $em=$manager->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('Auth_Aff');
        }
        return $this->render('author/ajout.html.twig',['f'=>$form->createView()]);
        #return $this->renderForm('author/Ajout.html.twig',['form'=>$form]);
    }

    #[Route('/modifier/{id}',name:'auth_modifier')]
    function modifier(Request $req, ManagerRegistry $manager,AuthorRepository $repo,$id){
        $author=$repo->find($id);        
        $form=$this->createForm(AuthorType::class,$author);
        $form->add('Modifier',SubmitType::class);//creation du btn
        $form->handleRequest($req);
        //Add
        if($form->isSubmitted() && $form->isValid()){
            $em=$manager->getManager();
            $em->flush();
            return $this->redirectToRoute('Auth_Aff');
        }
        return $this->renderForm('author/modifier.html.twig',['f'=>$form]);
    }

    #[Route('/Supprimer/{id}',name:'auth_supprimer')]
    function supprimer(ManagerRegistry $manager,AuthorRepository $repo,$id){
        $author=$repo->find($id);
        $em=$manager->getManager();
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute('Auth_Aff');
    }
        #[Route('/afficheByEmail')]
    function AfficheByEmail( AuthorRepository $repo){
       // $authors = $repo->listAuthorByEmail();     
       $authors = $repo->listAuthorByEmailDQL();                                      
        return $this->render('author/affiche.html.twig',['authors'=>$authors]);                       
    }
    #[Route('/RechercheRef', name:'R')]
    function SearchRef(Request $req,AuthorRepository $repo){
        $nom=$req->get('nom');
        $author=$repo->searchAuth($nom);
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($author, 'json');
        return new Response($jsonContent);      
    }

}
