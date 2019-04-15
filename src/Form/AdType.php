<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ApplicationType;
use  App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {      
       //Gestion du formulaire de contact  le label et placeholder
        $builder
        ->add('title', TextType::class, $this->getConfiguration("Titre","tapez un super titre pour votre annonnce"))
            ->add('slug',TextType::class, $this->getConfiguration("Adresse web", "Tapez l'adresse web (automatque)", 
             ['required'=>false])
             )
            ->add('coverImage', UrlType::class, $this->getConfiguration("Url de de l'image principale", "donnez l'adresse d'une image
                   qui donne vraiment envie !"))
            ->add('content', TextareaType::class, $this->getConfiguration("Description detaillée", "Tapez une description detaillée"))
            ->add('introduction',TextType::class, $this->getConfiguration(" Introduction", "Donnez une description globale de l'annonce"))
            ->add('rooms', IntegerType::class, $this->getConfiguration(" Nombres de chambrse", "Le nombre de chambres disponibles ") ) 
            ->add('price', MoneyType::class, $this->getConfiguration("Prix par nuit", "Indiquez le prix que vous voulez pour une nuit "))
               //J'ai rajouté  un sous formulaire   au formulaire principale 
            ->add('images', 
                  CollectionType::class,
                [  
                  'entry_type'=>ImageType::class, 'allow_add'=>true,'allow_delete'=>true
                ]
                );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
