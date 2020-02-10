<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 25/06/19
 * Time: 17:27
 */

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @method User getUser()
 */
abstract class BaseController extends AbstractController
{
//    protected function getUser() : User
//    {
//        return parent::getUser();
//    }

}