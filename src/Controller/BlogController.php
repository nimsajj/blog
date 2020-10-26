<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\FileUploader;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(
            ["isPublished" => true],
            ['publishedAt' => 'desc']
        );

        return $this->render('blog/index.html.twig', ["articles" => $articles]);
    }

    /**
     * @Route("/add", name="article_add")
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request, FileUploader $fileUploader)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($article->getIsPublished()) {
                $article->setPublishedAt(new \DateTime());
            }

            if ($article->getPicture() !== null) {
                $file = $form->get('picture')->getData();
                $fileName = $fileUploader->upload($file);
                $article->setPicture($fileName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('blog/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/show/{id}", name="article_show")
     */
    public function show(Article $article)
    {
        return $this->render('blog/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="article_edit", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Article $article, Request $request, FileUploader $fileUploader)
    {
        $oldPicture = $article->getPicture();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTime());

            if ($article->getIsPublished()) {
                $article->setPublishedAt(new \DateTime());
            }

            if ($article->getPicture() !== null && $article->getPicture() !== $oldPicture) {
                $file = $form->get('picture')->getData();
                $fileName = $fileUploader->upload($file);

                $article->setPicture($fileName);
            } else {
                $article->setPicture($oldPicture);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('blog/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove/{id}", name="article_remove", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function remove(int $id, FileUploader $fileUploader)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository(Article::class)->findOneBy(["id" => $id]);

        if (!$article) {
            throw $this->createNotFoundException('L\'article n\'existe pas');
        }

        $fileUploader->remove($article->getPicture());

        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('homepage');
    }


    /**
     * @Route("/admin", name="admin_page")
     */
    public function admin()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(
            [],
            ['publishedAt' => 'desc']
        );

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render("admin/index.html.twig", [
            'articles' => $articles,
            'users' => $users
        ]);
    }
}
