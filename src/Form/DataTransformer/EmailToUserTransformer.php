<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 28/06/19
 * Time: 15:23
 */

namespace App\Form\DataTransformer;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

 class EmailToUserTransformer implements DataTransformerInterface
{

     /**
      * @var UserRepository
      */
     private $UserRepository;

     public function __construct(UserRepository $UserRepository)
     {
         $this->UserRepository = $UserRepository;
     }


     public function transform($value)
    {
        if (null === $value){
            return '';
        }
        if (!$value instanceof User){
            throw new \LogicException('The UserSelectTextType can only be used with user object.');
        }
        return $value->getEmail();
    }

    public function reverseTransform($value)
    {

        if (!$value){
            return ;
        }
        $user = $this->UserRepository->findOneBy(['email'=> $value]);

        if (!$user){
            throw new TransformationFailedException(sprintf(
                'No User found with email %s',$value
            ));
        }

        return $user;

    }

}