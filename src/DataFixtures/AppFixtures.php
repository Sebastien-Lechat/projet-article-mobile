<?php

namespace App\DataFixtures;
use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Création de Faker 
        $faker=Factory::create('FR-fr');
        //instanciation de slugify  histoire creer un titre et d'en faire une url 
        //$slugify=  new  Slugify();
        //ON boucle 
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
            //Remplacement des données ecris en dur 
        $ad->setTitle($title)
           ->setContent($content)
           ->setPrice(mt_rand(55,100))
           ->setIntroduction($introdution)
           ->setRooms(mt_rand(2,9))
           ->setCoverImage($coverImage);

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
