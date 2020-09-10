<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface{

    public function transform($date){
            if($date === null)
                return '';
            return $date->format('d/m/Y');
    }

    public function reverseTransform($frenchDate){
        if($frenchDate === null){
            //exception
            throw new TransformationFailedException('Vous devez fournire une date !');
        }

        $date = \DateTime::createFromFormat('d/m/Y',$frenchDate);

        if($date === false){
            //exception
            throw new TransformationFailedException('le format de la date n\'est pas bon');
        }
        return $date;
    }
}