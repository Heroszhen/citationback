<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\User;

class UserPasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\Password $constraint */
/*
        if (null === $value || '' === $value) {
            return;
        }

        if($value instanceof User) {
            return;
        }

        if($value->getId() != null) {
            return;
        }*/

        $checked = true;
        $password = $value->getPassword();
        if($password == "" || $password == null ||
            strlen($password) < 8 ||
            !preg_match('@[A-Z]@', $password) ||
            !preg_match('@[a-z]@', $password)
          ) {
            $checked = false;
        }

        if($checked == true) {
            $checked = false;
            $tab = [",",";",":","?",".","/","@","#","\"","\'","{","}","[","]","-","_","(",")","$","*","%","=","+"];
            foreach($tab as $item){
                if(strpos($password, $item) !== false){
                    $checked = true;
                    break;
                }
            }
        }
    
        if($checked == false) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $password)
                ->addViolation();
        }
    }
}
