<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;


class  FrenchToDateTimeTransformer implements  DataTransformerInterface {


    public function transform($date){
       

        if($date === null){

            return '';
        }
        return $date->format('d/m/Y');

    }

      //Permet de transformer la date en format français

    public function reverseTransform($frenchDate){
       //frenchDate= 13/04/2019

       if($frenchDate === null){

           //Exception 
           throw new TransformationFailedException("Vous devez fournir une date !");
         
           
       }
     
       $date= \DateTime::createFromFormat('d/m/Y', $frenchDate);
       
        if($date ===  false ){

            //Exeption 
            throw new TransformationFailedException("Le format de la date n'est pas le bon  !");
        }

        return $date;

    }
}