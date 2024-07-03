<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
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
#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/affiche',name:'Book_Aff')]
    public function affiche(BookRepository $bookRepository){
        $books = $bookRepository->findAll();
        return $this->render('book/affiche.html.twig',['books'=>$books]);
    }
    #[Route('/Ajout')]
    function Ajout(Request $req,ManagerRegistry $manager){
        //Form
        $book=new Book();
        $form=$this->createForm(BookType::class,$book);
        $form->add('Ajout',SubmitType::class);
        $form->handleRequest($req);
        //Add
        if($form->isSubmitted() && $form->isValid()){
            $em=$manager->getManager();
            $book->setPublished(true);
            $nb=$book->getAuthor()->getNbBooks()+1;
            $book->getAuthor()->setNbBooks($nb);
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('Book_Aff');
        }
        return $this->render('book/Ajout.html.twig',['f'=>$form->createView()]);
        #return $this->renderForm('book/Ajout.html.twig',['form'=>$form]);
    }
    #[Route('/Modifier/{id}',name:'modifier')]
    function modifier(Request $req, ManagerRegistry $manager,BookRepository $repo,$id){
        $book=$repo->find($id);        
        $form=$this->createForm(BookType::class,$book);
        $form->add('Modifier',SubmitType::class);//creation du btn
        $form->handleRequest($req);
        //Add
        if($form->isSubmitted() && $form->isValid()){
            $em=$manager->getManager();
            $em->flush();
            return $this->redirectToRoute('Book_Aff');
        }
        return $this->renderForm('book/modifier.html.twig',['f'=>$form]);
    }
    #[Route('/Supprimer/{id}',name:'supprimer')]
    function supprimer(ManagerRegistry $manager,BookRepository $repo,$id){
        $book=$repo->find($id);
        $em=$manager->getManager();
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('Aff');
    }
   /* #[Route('/Recherche',name:'Rech')]
    function Search(Request $req,BookRepository $repo){
        $ref=$req->get('title');
        $book=$repo->findByTitle($ref,['publicationDate' => 'ASC']);
        return $this->render('book/affiche.html.twig',['books'=>$book]);
    }*/
    #[Route('/RechercheRef',name:'Rech')]
    function SearchRef(Request $req,BookRepository $repo){
        $ref=$req->get('title');
        $book=$repo->searchBookByRef($ref);
        return $this->render('book/affiche.html.twig',['books'=>$book]);
    }
    #[Route('/RechercheAuteur',name:'RechAuteur')]
    function SearchAuteur(Request $req,BookRepository $repo){
        $book=$repo->BooksByAuthor();
        return $this->render('book/affiche.html.twig',['books'=>$book]);
    }

     #[Route('/RechercheBook')]
    function Books(Request $req,BookRepository $repo){
        // $book=$repo->BooksByDateNb();
        $book=$repo->BookByDateDQL();
        return $this->render('book/affiche.html.twig',['books'=>$book]);
    }
#[Route('/RechAjax', name:'RechAjax')]
    function SearchAjax(Request $req,BookRepository $repo){
        $ref=$req->get('ref');
        $book=$repo->searchBookByRef($ref);
        echo $book;
        $encoder=[new XmlEncoder(), new JsonEncoder()];
        $normalizer=[new ObjectNormalizer()];
        $serialize=new Serializer($normalizer,$encoder);
        $jsonContent=$serialize->serialize($book,'json');
        echo $jsonContent;
        return new Response($jsonContent);
       // return $this->render('book/affiche.html.twig',['books'=>$book]);
    }

}
