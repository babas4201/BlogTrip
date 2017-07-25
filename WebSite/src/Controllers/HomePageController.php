<?php
/**
 * Created by PhpStorm.
 * User: plaza
 * Date: 05/01/2017
 * Time: 14:39
 */

namespace DUT\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use DUT\Models\Article;


class HomePageController
{
    public function __construct()
    {

    }

    //Renvoie les articles les plus récent pour la page d'acceuil
    public function recentArticle(Application $app){
        $em = $app['em'];
        $repository = $em->getRepository('DUT\\Models\\Article');
        $articles = $repository->findby(array(),['arDate'=>'DESC'],3);

        return $articles;
    }

    //Retourne le nb d'article dans la BD
    public function isArticle(Application $app){
        $nbArticles = 0;
        $em = $app['em'];
        $repository = $em->getRepository('DUT\\Models\\Article');
        $articles = $repository->findAll();
        foreach ($articles as $article){
            $nbArticles++;
        }
        return $nbArticles;
    }

    //Renvoie le nb article et l'article pour affichage
    //Renvoie dans le fichier twig
    public function display(Application $app){
        if($this->isArticle($app) == 0){
            $nbArticle = 0;
            $articles = null;
        }
        elseif ($this->isArticle($app) < 4){
            $nbArticle = $this->isArticle($app);
            $articles = $this->recentArticle($app);
        }
        else{
            $nbArticle = 3;
            $articles = $this->recentArticle($app);
        }

        return $app['twig']->render('basedIndex.twig',['articles' => $articles,'nbArticle' => $nbArticle]);
    }

}