<?php
/**
 * Created by PhpStorm.
 * User: plaza
 * Date: 11/01/2017
 * Time: 09:15
 */

namespace DUT\Controllers;

use DUT\Models\Article;
use DUT\Models\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;

//Controller Administration des commentaires
class AdministrationCommentController{

    //Suppression d'un commentaire
    public function AdministrationSuppComment(Application $app, Request $request, $id , $idArticle){
        $em = $app['em'];
        $commentToRemove = $em->find('DUT\\Models\\Comment', $id);
        $em->remove($commentToRemove);
        $em->flush();

        return $app->redirect($request->getBaseUrl().'/administration/manage/'.$idArticle);
    }

    //Modification d'un commentaire : Affichage du formulaire et traitement en cas d'envoie
    public function AdministrationModComment(Application $app, Request $request, $id , $idArticle){
        $em = $app['em'];
        $repository = $em->getRepository('DUT\\Models\\Comment');
        $comment = $repository->find($id);

        /*
         * traitement du formulaire de modification de commentaire
         */

        // On créé l'objet form à partir du formBuilder (En passant en param l'objet form)
        $data = ['coDescription' => $comment->getCoDescription()];
        $form = $app['form.factory']->createBuilder('form',$data)
            //On ajoute chaque champs de notre form
            ->add('coPseudo', 'text', array(
                'required' => true,
                'attr' => array('value' => $comment->getCoPseudo())
            ))
            ->add('coDescription', 'textarea', array(
                'required' => true,
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 15))),
                'attr' => array('cols' => '5', 'rows' => '3', 'placeholder' => $comment->getCoDescription())
            ))
            ->getForm(); // On récupère l'objet form

        if ('POST' == $request->getMethod()){ // Si on a soumis le formulaire
            $form->bind($request); // On bind les valeurs du POST à notre formulaire
            if ($form->isValid() && $form->isSubmitted()){ // On teste si les données entrées dans notre formulaire sont valides

                $data = $form->getData(); //Met les données dans data
                $em = $app['em'];
                $repository = $em->getRepository('DUT\\Models\\Comment');

                $CommentToRemove = $em->find('DUT\\Models\\Comment', $id);
                $em->remove($CommentToRemove);
                $em->flush();

                // Insertion d'un commentaire dans la BD
                $newComment= new Comment($idArticle, $data['coPseudo'], $data["coDescription"]);
                $newComment= new Comment($idArticle, $data['coPseudo'], $data["coDescription"]);
                $em->persist($newComment);
                $em->flush();
                return $app->redirect($request->getBaseUrl().'/administration/manage/'.$idArticle);
            }
        }

        return $app['twig']->render('basedAdminManageComment.twig"', ['form'  => $form->createView(), 'idArticle' => $idArticle]);
    }
}