<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 29/06/19
 * Time: 20:25
 */

namespace App\Form\Model;

use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank(message="Please enter an email")
     * @Assert\Email()
     * @UniqueUser()
     */
    public $email;

    /**
     * @Assert\NotBlank(message="Choose a Password!")
     * @Assert\Length(min=5, minMessage="Come on, you can think of a password longer than that!")
     */
    public $plainPassword;

    /**
     * @Assert\IsTrue(message="vous devez cocher cette case")
     */
    public $agreeTerms;
}