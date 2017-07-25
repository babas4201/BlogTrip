<?php
/**
 * Created by PhpStorm.
 * User: plaza
 * Date: 05/01/2017
 * Time: 17:54
 */

namespace DUT\Controllers;

use DUT\Models\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use DUT\Models\Article;
use Symfony\Component\Validator\Constraints as Assert;

class CommentArticleController
{
    public function __construct()
    {

    }


    //Renvoie les commentaires d'un article en fonction de son id
    public function returnComment(Application $app , $idArticle){
        $em = $app['em'];
        $repository = $em->getRepository('DUT\\Models\\Comment');
        $comments = $repository->findBy(['arId' => $idArticle], ['coId' => 'ASC']);
        return $comments;
    }

    //Renvoie tous l'affichage concernant l'article et les commentaires
    public function displayArticle(Application $app , Request $request, $id){
        $em = $app['em'];
        $repository = $em->getRepository('DUT\\Models\\Article');
        $article = $repository->find($id); //Retourne l'id de l'article
        $comments = $this->returnComment($app, $id); //Retourne tous les commentaires

        /*
         * traitement du formulaire de commentaire
         */

        // On créé l'objet form à partir du formBuilder (En passant en param l'objet form)
        $form = $app['form.factory']->createBuilder('form')
            //On ajoute chaque champs de notre form
            ->add('commentPseudo', 'text', array(
                'required' => true,
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
            ))
            ->add('commentDescription', 'textarea', array(
                'required' => true,
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
            ))
            ->getForm(); // On récupère l'objet form

        if ('POST' == $request->getMethod()){ // Si on a soumis le formulaire
            $form->bind($request); // On bind les valeurs du POST à notre formulaire
            if ($form->isValid()){ // On teste si les données entrées dans notre formulaire sont valides
                $data = $form->getData(); //Met les données dans data

                // Insertion du commentaire dans la BD
                $newComment = new Comment($id, $data["commentPseudo"], $data["commentDescription"]);
                $em->persist($newComment);
                $em->flush();
                return $app->redirect($id);
            }
        }

        /*
         * fin traitement du formulaire de commentaire
         */
        return $app['twig']->render('basedCommentArticle.twig', ['article' => $article , 'comments' => $comments, 'form'  => $form->createView()]);
    }
}