<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\MarkdownHelper;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ArticleController extends AbstractController
{
    /**
     * Currently unused: just showing a controller with a constructor!
     */
    private $isDebug;

    public function __construct(bool $isDebug)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(ArticleRepository $repository)//EntityManagerInterface $em
    {
//        $repository = $em->getRepository(Article::class);
//        $articles = $repository->findAll();
//        $articles = $repository->findBy([],['publisheAt' => 'DESC']);//findPublisheAOrderByNewset

        $articles = $repository->findPublisheAOrderByNewset();
        return $this->render('article/homepage.html.twig',[
            'articles' => $articles
       ] );
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show(Article $article)//$slug,EntityManagerInterface $em
    {
//        SlackClient $slack
//        if ($slug === 'khaaaaaan') {
//            $slack->sendMessage('Kahn', 'Ah, Kirk, my old friend...');
//        }

//        $repository = $em->getRepository(Article::class);
//        $article = $repository->findOneBy(['slug' => $slug]);

//        if (!$article){
//
//            $this->createNotFoundException(sprintf('No article for  slug "%s"',$slug));
//            // custom page error symfony
//        }
//
        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];



        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart(Article $article, LoggerInterface $logger,EntityManagerInterface $em)
    {
        // TODO - actually heart/unheart the article!

//        $article->setHeartCount($article->getHeartCount() + 1);//incrementHeartCount
        $article->incrementHeartCount();
        $em->flush();

        $logger->info('Article is being hearted!');

        return new JsonResponse(['hearts' => $article->getHeartCount()]);
    }



}
