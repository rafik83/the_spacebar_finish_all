<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 20/06/19
 * Time: 20:05
 */

namespace App\Controller;


use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ArticleFormType;



class ArticleAdminController extends BaseController
{


    /**
     * @Route("/admin/article/new", name="admin_article_new")
     */
    public function new(EntityManagerInterface $em,Request $request){
        //    * @IsGranted("ROLE_ADMIN_ARTICLE")
        $form = $this->createForm(ArticleFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {


            $article = $form->getData();
//            dd($article);
//            $article = new Article();
//            $article->setTitle($data['title']);
//            $article->setContent($data['content']);
//            $article->setAuthor($this->getUser());
            $em->persist($article);
            $em->flush();
            $this->addFlash('success','Article Created! Knowledge is power!');

            return $this->redirectToRoute('admin_article_list');
        }
       return $this->render('article_admin/new.html.twig',[
           'articleForm'=> $form->createView(),

        ]);
    }


    /**
     * @Route("/admin/article/{id}/edit", name="admin_article_edit")
     *
     */

    public  function edit(Article $article,Request $request,EntityManagerInterface $em)
    {
        //* @IsGranted("MANAGE",subject="article")

        $form = $this->createForm(ArticleFormType::class,$article,[
            'include_published_at' => true,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em->persist($article);
            $em->flush();
            $this->addFlash('success','Article Update! Inaccuracies Squashed');
            $this->redirectToRoute('admin_article_edit',[
                'id'=> $article->getId(),
            ]);

        }

        return $this->render('article_admin/edit.html.twig',[
            'articleForm'=> $form->createView()
        ]);
        //$article->getAuthor() != $this->getUser() && !$this->isGranted('ROLE_ADMIN_ARTICLE')
//        if (!$this->isGranted('MANAGE',$article)){
//            throw  $this->createAccessDeniedException('no access !');
//        }
//            $this->denyAccessUnlessGranted('MANAGE',$article);

    }


    /**
     * @Route("/admin/article/location-select", name="admin_article_location_select")
     * @IsGranted("ROLE_USER")
     *
     */
    public function getSpecificLocationNameField(Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN_ARTICLE') && $this->getUser()->getArticles()->isEmpty()){

            throw  $this->createAccessDeniedException();
        }
        $article = new Article();
        $article->setLocation($request->query->get('location'));
        $form = $this->createForm(ArticleFormType::class,$article);
        if (!$form->has('specificLocationName')){
            return new Response(null,204);
        }



        return $this->render('article_admin/_specific_location_name.html.twig',[

           'articleForm' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/article", name="admin_article_list")
     *
     */
    public function list(ArticleRepository $articleRepo)
    {



        $articles = $articleRepo->findAll();
        return $this->render('article_admin/list.html.twig',[
            'articles'=> $articles

            ]);
    }
}