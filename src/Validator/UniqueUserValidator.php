<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }


    public function validate($value, Constraint $constraint)
    {
//        /* @var $constraint \App\Validator\UniqueUser */
//
//        if (null === $value || '' === $value) {
//            return;
//        }

        $existeUser = $this->userRepo->findOneBy([
          'email'=> $value
        ]);

        if (!$existeUser){
            return ;
        }

        $this->context->buildViolation($constraint->message)
//            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
