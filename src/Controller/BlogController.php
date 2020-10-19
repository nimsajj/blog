<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return  new Response('<h1>Home page</h1>');
    }

    /**
     * @Route("/add", name="article_add")
     */
    public function add()
    {
        return new Response('<h1>Ajouter un article</h1>');
    }

    /**
     * @Route("/show/{slug}", name="article_show")
     */
    public function show(string $slug)
    {
        return new Response('<h1>Lire l\'article ' .$slug. '</h1>');
    }
    
    /**
     * @Route("/edit/{id}", name="article_edit", requirements={"id"="\d+"})
     */
    public function edit(int $id)
    {
        return new Response('<h1>Modifier l\'article ' .$id. '</h1>');
    }

     /**
     * @Route("/remove/{id}", name="article_remove", requirements={"id"="\d+"})
     */
    public function remove(int $id)
    {
        return new Response('<h1>Supprimer l\'article ' .$id. '</h1>');
    }
}

