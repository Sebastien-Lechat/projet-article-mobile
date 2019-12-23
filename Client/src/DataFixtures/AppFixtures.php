<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Flux;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class AppFixtures extends Fixture
{
    /**
     * L'encodeur du mot de passe
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

     public function __construct(UserPasswordEncoderInterface $encoder){
         $this->encoder=$encoder;
     }
    public function load(ObjectManager $manager)
    {
        
        $faker=Factory::create('FR-fr');
          $userAdmin= new User();
          $userAdmin->setFirstname('lamine')
                    ->setLastname('DIAKITE')
                    ->setEmail('diakitelamine555@gmail.com')
                    ->setPassword($this->encoder->encodePassword($userAdmin, 'password'))
                    ->setAvatar('https://randomuser.me/api/portraits/men/77.jpg');
         $manager->persist($userAdmin);

             $users=[];
             // Declaration Genres 
            $genres= ['male', 'female'];
             //on créé 10 personnes
        for ($c = 0; $c < 10; $c++) {
            $user = new User();

              //Recuperation de genre
              $genre=$faker->randomElement($genres);
              //Recuperation pour les avatars 
              $avatar='https://randomuser.me/api/portraits/';
              //Choix des photos entre 1 et 99
              $avatarId=$faker->numberBetween(1, 99). '.jpg';
              //Choix des genres 
              $avatar .=($genre=='male'? 'men/' : 'women/').$avatarId;
              $hash=$this->encoder->encodePassword($user, "password");
              $user->setFirstname($faker->firstname())
                   ->setLastname($faker->lastname)
                   ->setEmail($faker->email)
                   ->setPassword($hash)
                   ->setAvatar($avatar);
                   $users[]=$user;
           $manager->persist($user);
  
            for ($i = 0; $i < mt_rand(3, 12); $i++) {
                $article = new Article();
                $title=$faker->sentence(); 
                $subtitle= $faker->paragraph(1);
                $description='<p>'. join('</p><p>', $faker->paragraphs(5)) . '</p>';
                $createAt=$faker->dateTimeBetween(' -6 months');
                $updateAt=$faker->dateTimeBetween('-3 months ');
               $img=$faker->imageURL(1000, 35);
               $link=$faker->URL(800, 250);
                $article->setTitle($title)
                         ->setSubtitle($subtitle)
                         ->setDescription($description)
                         ->setCreatedAt($createAt)
                         ->setUpdateAt($updateAt)
                         ->setImg($img)
                         ->setLink($link)
                         ->setUser($user);
                 $manager->persist($article);
            }

            for ($j = 0; $j < mt_rand(3,10); $j++) {
                   $flux= new Flux();
                   $createAt=$faker->dateTime(' -6 months');
                   $updateAt=$faker->dateTime('-3 months ');
                   $flux->setName($faker->name())
                        ->setLink($faker->URL(800, 250))
                        ->setCreatedAt($createAt)
                        ->setUpdateAt($updateAt)
                        ->setUser($user);
                $manager->persist($flux);
            }
        }

    
            
            $manager->flush();  
    }


}