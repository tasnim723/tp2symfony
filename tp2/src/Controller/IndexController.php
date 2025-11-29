<?php

// src/Controller/IndexController.php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route; // Routing moderne
use Doctrine\ORM\EntityManagerInterface;       // CRUD (Persist/Flush/Remove)
use App\Entity\Article;                         // Entité Article
use App\Repository\ArticleRepository;           // Lecture (Find/FindAll)
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\ArticleType;

class IndexController extends AbstractController
{
    // --- 1. Opération CREATE (Ajout de test - Étape 7 du TP) ---
    // Remplacement de l'annotation /** @Route("/article/save") */
    // et injection de l'EntityManagerInterface
    #[Route('/article/save')]
public function save(EntityManagerInterface $entityManager): Response // Injection!
{
    $article = new Article();
    $article->setNom('Article 1');
    $article->setPrix(1000); // Test data
    
    $entityManager->persist($article);
    $entityManager->flush();
    
    return new Response('Article enregistré avec id ' . $article->getId());
}

    // --- 2. Opération READ ALL (home - Étape 11 du TP) ---
    // Remplacement de l'annotation et injection du Repository
    #[Route('/', name: 'article_list')]
public function home(ArticleRepository $articleRepository): Response // Injection du Repository!
{
    $articles = $articleRepository->findAll();

    return $this->render('articles/index.html.twig', ['articles' => $articles]);
}

 // --- 3. Opération CREATE via formulaire (new - Étape 14 du TP) ---
    // Remplacement des annotations @Route/@Method et injection de l'EntityManager
    #[Route('/article/new', name: 'new_article', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $article = new Article();
   /* $form = $this->createFormBuilder($article)
        ->add('nom', TextType::class)
        ->add('prix', TextType::class)
        ->add('save', SubmitType::class, ['label' => 'Créer'])
        ->getForm();
*/
$form = $this->createForm(ArticleType::class, $article);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($article);
        $entityManager->flush();
        return $this->redirectToRoute('article_list');
    }

    return $this->render('articles/new.html.twig', ['form' => $form->createView()]);
}


    // --- 4. Opération READ ONE (show - Étape 19 du TP) ---
    // Remplacement de l'annotation et utilisation de l'auto-injection de l'objet Article
    #[Route('/article/{id}', name: 'article_show')]
public function show(Article $article): Response // Auto-injection de l'objet!
{
    return $this->render('articles/show.html.twig', [
        'article' => $article
    ]);
}

   
  #[Route('/article/edit/{id}', name: 'edit_article', methods: ['GET', 'POST'])]
public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response // Injection de l'objet et du Manager!
{
   $form = $this->createForm(ArticleType::class, $article);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush(); // Les modifications sont enregistrées automatiquement
        return $this->redirectToRoute('article_list');
    }

    return $this->render('articles/edit.html.twig', ['form' => $form->createView()]);
}

#[Route('/article/delete/{id}', name: 'delete_article', methods: ['DELETE', 'POST'])] 
public function delete(Article $article, EntityManagerInterface $entityManager): Response
{
    $entityManager->remove($article);
    $entityManager->flush();
    
    return $this->redirectToRoute('article_list');
}

}
?>