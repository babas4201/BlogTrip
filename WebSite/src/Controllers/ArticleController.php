<?php
/**
 * Created by PhpStorm.
 * User: plaza
 * Date: 05/01/2017
 * Time: 14:37
 */

namespace DUT\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use DUT\Models\Article;

class ArticleController
{
    public function __construct()
    {

    }

    //Renvoie les articles
    public function returnArticles(Application $app){
        $em = $app['em'];
        $repository = $em->getRepository('DUT\\Models\\Article');
        $articles = $repository->findby(array(),['arDate'=>'DESC']);
        return $articles;
    }

    //Renvoie l'affichage des articles
    public function displayArticles(Application $app){
        $articles = $this->returnArticles($app);
        return $app['twig']->render('basedArticles.twig', ['articles' => $articles]);
    }
}

