<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        
        $faker = Factory::create('FR-fr');

        $users = [];
        //nous gerons les utilisateurs ici
        for($i = 1;$i <= 10; $i++){
            $user = new User();

            $hash = $this->encoder->encodePassword( $user,'password');

            $user->setFirstName($faker->firstname)
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription("<p>".join('</p><p>',$faker->paragraphs(3))."</p>")
                 ->setHash($hash);

            $manager->persist($user);

            $users[] = $user;
        }



        //nous gerons les  annonces ici
        for($i = 1;$i <= 30; $i++){

            $ad = new Ad();

            $user = $users[mt_rand(0,count($users) - 1)];

            $tille = $faker->sentence();
            $coverImage = $faker->imageUrl(1000,300);
            $introduction = $faker->paragraph(2);
            $content = "<p>".join('</p><p>',$faker->paragraphs(5))."</p>";
            $ad->setTitle($tille)
            ->setCoverImage($coverImage)
            ->setIntroduction($introduction)
            ->setContent($content)
            ->setPrice(mt_rand(40,100))
            ->setRooms(mt_rand(1,5))
            ->setAuthor($user);

            

            for($j = 1 ;$j <= mt_rand(2,5); $j++){

                $image = new Image();
                $image->setUrl($faker->imageUrl(1000,300))
                      ->setCaption($faker->sentence())
                      ->setAd($ad);
                      
                $manager->persist($image);
            }

            $manager->persist($ad);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
