<?php

namespace App\DataFixtures;

use App\Entity\Newsletter;
use App\Entity\Post;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Date;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $fake = Factory::create();

        for ($u=0; $u < 10 ; $u++) { 
            # code...
            $user = new User();

            $passwordHash = $this->encoder->hashPassword($user, plainPassword: 'password');

            $user->setEmail($fake->email)
                ->setPassword($passwordHash)
                ->setUsername($fake->userName)
                ->setLastname($fake->lastName)
                ->setFirstname($fake->firstName)
                ->setCreatedAt(new DateTimeImmutable());
            
            
            $manager->persist($user);

            for ($a=0; $a < random_int(5, 15); $a++) { 
                # code...
                $post = (new Post)->setAuthor($fake->name)
                                        ->setContent($fake->text(maxNbChars: 300))
                                        ->setTitle($fake->title)
                                        ->setImage($fake->imageUrl(640, 480, null, true))
                                        ->setCreatedAt(new DateTimeImmutable());

                
                $manager->persist($post);
            }
            
            $newsletter = new Newsletter();

                $newsletter->setReaderEmail($fake->email)
                            ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($newsletter);
        }

        $manager->flush();
    }
}
