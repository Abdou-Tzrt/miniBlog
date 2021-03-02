<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $fake = Factory::create();

        for ($u = 0; $u < 10; $u++)
        {
            $user = new User();
            $passHash = $this->encoder->encodePassword($user,'password');
            $user->setEmail($fake->email)
                ->setPassword($passHash);

            if ($u % 3 === 0)
            {
                $user->setStatus(false)
                    ->setAge(22);
            }

            $manager->persist($user);
        }

        for ($a = 0; $a < random_int(5,15); $a++)
        {
            $article = (new Article())->setAuthor($user)
                ->setContent($fake->text(300))
                ->setName($fake->text(50));
            $manager->persist($article);
        }

        $manager->flush();
    }
}
