<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    private $em;
    const NB_ARTICLE = 8;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function load(ObjectManager $manager)
    {
        $users = $this->em->getRepository(User::class)->findAll();
        $categories = $this->em->getRepository(Category::class)->findAll();

        for ($i = 1; $i <= self::NB_ARTICLE; $i++) {
            $article = new Article();
            $article->setTitle("Article " . $i);
            $article->setPicture("empty-picture.jpg");
            $date = new \DateTime();
            $date->add(new \DateInterval('PT' . $i . 'H'));

            $article->setPublishedAt($date);
            $article->setIsPublished(true);
            $article->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.");

            if ($i % 2 === 0) {
                $article->setAuthor($users[0]);
            } else {
                $article->setAuthor($users[1]);
            }

            foreach ($categories as $k => $category) {
                if ($k === 3) break;
                $article->addCategory($category);
            }

            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class
        ];
    }
}
