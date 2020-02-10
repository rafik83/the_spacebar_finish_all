<?php

namespace App\Controller;

use App\Repository\UserRepository;
use  Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;




class AdminUtilityController extends  AbstractController
{

    /**
     * @Route("/admin/utility/users", methods="GET", name="admin_utility_users")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function getUsersApi(UserRepository $userRepos,Request $request)
    {
//        $users = $userRepos->findAllEmailAlphabetical();

        $users = $userRepos->findAllMatching($request->query->get('query'));

        return $this->json(['users'=> $users],200,[],
            ['groups'=> ['main']]// serialisation
        );




    }

}