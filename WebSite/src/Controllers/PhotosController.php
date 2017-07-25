<?php
/**
 * Created by PhpStorm.
 * User: plaza
 * Date: 05/01/2017
 * Time: 17:10
 */

namespace DUT\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class PhotosController
{
    public function __construct()
    {

    }

    //Renvoie tous les url des photos
    public function displayPhotos(Application $app){
        $em = $app['em'];
        $repository = $em->getRepository('DUT\\Models\\Article');
        $articles = $repository->findby(array(),['arDate'=>'DESC']);
        return $app['twig']->render('basedPhotos.twig', ['photo' => $articles]);
    }
}