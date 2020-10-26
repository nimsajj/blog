<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setEmail("pelletier.jas@gmail.com");
        $userAdmin->setUsername("jasmin");
        $userAdmin->setPassword(
            $this->passwordEncoder->encodePassword(
                $userAdmin,
                "selmas"
            )
        );
        $userAdmin->addRoles("ROLE_ADMIN");

        $user = new User();
        $user->setEmail("pelletier.jasmin@gmail.com");
        $user->setUsername("jasmin");
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $userAdmin,
                "jasmin"
            )
        );
        $user->addRoles("ROLE_USER");

        $manager->persist($userAdmin);
        $manager->persist($user);

        $manager->flush();
    }
}
