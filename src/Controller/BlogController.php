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
        return $this->render('blog/index.html.twig');
    }

    /**
     * @Route("/add", name="article_add")
     */
    public function add()
    {
        return $this->render('blog/add.html.twig');
    }

    /**
     * @Route("/show/{slug}", name="article_show")
     */
    public function show(string $slug)
    {
        return $this->render('blog/show.html.twig', [
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="article_edit", requirements={"id"="\d+"})
     */
    public function edit(int $id)
    {
        return $this->render('blog/edit.html.twig', [
            'id' => $id,
        ]);
    }

    /**
     * @Route("/remove/{id}", name="article_remove", requirements={"id"="\d+"})
     */
    public function remove(int $id)
    {
        return new Response('<h1>Supprimer l\'article ' . $id . '</h1>');
    }
}
