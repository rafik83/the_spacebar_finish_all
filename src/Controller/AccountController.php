<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



/**
 * @IsGranted("ROLE_USER")
 *
 */
class AccountController extends BaseController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function index(LoggerInterface $logger)
    {

        $logger->debug('Cheking account page for '.$this->getUser()->getEmail());
            return $this->render('accounter/index.html.twig', [
        ]);

    }


    /**
     * @Route("/api/account", name="api_account")
     */
    public function accountApi()
    {
        // will return the JSON respresentation of whoever is logged in
        $user = $this->getUser();
        return $this->json($user,200,[],[
            'groups'=> ['main']// serialisation
        ]);
    }
}
