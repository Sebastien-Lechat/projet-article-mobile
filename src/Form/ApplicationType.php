<?php

namespace App\Form;
use Symfony\Component\Form\AbstractType;




 class ApplicationType extends AbstractType
 {
      /**
        * Permet d'avoir la configuration de base d'un champ
        *@param string $label
        *@param string $placeholder
        *@param array $options
        *@return array
        */
        // Pour evietr de faire de repeter de chaque contenir d'une balise 
        protected function getConfiguration ($label, $placeholder, $options=[])
        { 
          // On retourne le label et placeholder dans un tableau, en tenant compte de la creation automatque du slug 
           return array_merge([ 
               'label'=>$label, 
               'attr'=>['placeholder'=>$placeholder
               ]
           ],  $options);
        }


}