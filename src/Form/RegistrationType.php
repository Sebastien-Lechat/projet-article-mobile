<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
     

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class, $this->getConfiguration("Prénom", "Votre prénom..."))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Votre nom de famille..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre adresse email..."))
            ->add('ficture',UrlType::class, $this->getConfiguration("Photo de profit", "Url de votre Avatar..."))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe", "Choisi un bon mot de passe..."))
            ->add('passwordConfirm', passwordType::class, $this->getConfiguration("Confirmation de mot de passe",
            "Veuillez confirmer votre mot de passe !"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Présentez vous en quelques mots..."))
            ->add('description', TextareaType::class,$this->getConfiguration("Description detaillé ", "C'est le moment de vous presentez en details "))
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
