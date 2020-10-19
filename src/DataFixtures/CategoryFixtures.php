<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tags = ['php', 'symfony', 'javascript', 'python', 'java'];

        foreach ($tags as $tag) {
            $category = new Category();
            $category->setLabel($tag);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
