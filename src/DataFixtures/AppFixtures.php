<?php

namespace App\DataFixtures;
use App\Entity\User;
use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{ 
     //J'ai crée une propriété privée
      private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
      //Je vais le stocker dans am propriété
      $this->encoder=$encoder;

        
    }
    public function load(ObjectManager $manager)
    {
        // Création de Faker 
        $faker=Factory::create('FR-fr');
        //instanciation de slugify  histoire creer un titre et d'en faire une url 
        //$slugify=  new  Slugify();
        //ON boucle 
         // Nous gerons les utilisateurs 
          // on boucle 
          $users=[];
          $genres= ['male', 'female'];
          for($i=1; $i<=10; $i++){
             //Création d'une instance de la classe User
             $user= new  User();
            $genre=$faker->randomElement($genres);
            $ficture='https://randomuser.me/api/portraits/';
              //Choix des photos entre 1 et 99
            $fictureId=$faker->numberBetween(1, 99). '.jpg';
               //Choix des genres 
            $ficture .=($genre=='male'? 'men/' : 'women/').$fictureId;
             //Encodage du mot de passe 
            $hash=$this->encoder->encodePassword($user, 'password');
              
             

            $user->setFirstName($faker->firstName($genre))
                 ->setLastName($faker->lastName)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>'. join('</p><p>', $faker->paragraphs(3)) . '</p>')
                 ->setHash($hash)
                 ->setFicture($ficture);
                  $manager->persist($user);
                  $users[]=$user;


          }
        //Nous gerons les annonces 
        for($i=1; $i<=30; $i++){
        $ad= new Ad();
          $title=$faker->sentence();  
           //Recuperation du titre  de l'annonce 
          // $slug=$slugify->slugify($title);
          //calcul de slugify en fonction du title(majuscule, miniscule, caractere...)
          //$slug=$slugify->slugify($title);
          //Création des images differentes 
          $coverImage= $faker->imageURL(1000, 350);
            //Création de l'introducction qui vas prendre deux paragraphe
          $introdution= $faker->paragraph(2);
            //Création du contenu dans un  tableau  
           $content='<p>'. join('</p><p>', $faker->paragraphs(5)) . '</p>';
           $user= $users[mt_rand(0, count($users)-1)];
            //Remplacement des données ecris en dur 
        $ad->setTitle($title)
           ->setContent($content)
           //Choisi un prix entre 55 et 100
           ->setPrice(mt_rand(55,100))
           ->setIntroduction($introdution)
           ->setRooms(mt_rand(2,9))
           ->setCoverImage($coverImage)
           ->setAuthor($user);

           //Creation de l'image 

           for($j=1; $j<=mt_rand(2, 5); $j++ ){
              //Creation d'une instance de l'image  
            $image= new Image();

             $image->setUrl($faker->imageUrl())
                   ->setCaption($faker->sentence())
                   //Appartenance de l'image par rapport aux annonce
                   ->setAd($ad);
                   //La persistance 
                   $manager->persist($image);
           }

                $manager->persist($ad);
        }

               $manager->flush();
    }
}
