<?php
/**
 * Created by PhpStorm.
 * User: plaza
 * Date: 08/01/2017
 * Time: 15:03
 */

namespace DUT\Controllers;

use DUT\Models\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;

//Controller de gestion des articles
class AdministrationManageController
{
    //Modification d'un article en fonction de son ID reçu
    public function displayAdministrationModificationById(Application $app, Request $request, $id){
        $em = $app['em'];
        $repository = $em->getRepository('DUT\\Models\\Article');
        $comment = $repository->find($id);
        /*
         * traitement du formulaire de modification d'article
         */
        // On créé l'objet form à partir du formBuilder (En passant en param l'objet form)

        $data = ['arDescription' => $comment->getArDescription()];
        $form = $app['form.factory']->createBuilder('form',$data)
            //On ajoute chaque champs de notre form
            ->add('arTitle', 'text', array(
                'required' => true,
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
                'attr' => array('value' => $comment->getArTitle())
            ))
            ->add('arDescription', 'textarea', array(
                'required' => true,
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 15))),
                'attr' => array('cols' => '5', 'rows' => '3')
            ))
            ->add('arLink', 'file', array(
                'required' => false,
                'constraints' => array(new Assert\File(array(
                    'maxSize' => '8Mi',
                    'mimeTypes' => array(
                        'image/jpg',
                        'image/jpeg',
                        'image/png'
                    ),
                    'mimeTypesMessage' => 'Merci d\'inserer une image',
                )))))
            ->add('arDescPhoto', 'text', array(
                'required' => false,
                'attr' => array('value' => $comment->getArDescPhoto())
            ))
            ->getForm(); // On récupère l'objet form

        if ('POST' == $request->getMethod()){ // Si on a soumis le formulaire
            $form->bind($request); // On bind les valeurs du POST à notre formulaire
            if ($form->isValid() && $form->isSubmitted()){ // On teste si les données entrées dans notre formulaire sont valides

                $data = $form->getData(); //Met les données dans data
                $em = $app['em'];
                $repository = $em->getRepository('DUT\\Models\\Article');

                $articleToRemove = $em->find('DUT\\Models\\Article', $id);
                $em->remove($articleToRemove);
                $em->flush();

                // Insertion d'un article dans la BD
                $newArticle = new Article($data['arTitle'], $data["arDescription"], null, $data["arDescPhoto"]);
                $em->persist($newArticle);
                $em->flush();

                //Traitement de l'image
                if($form['arLink']->getData() != null){
                    $article = $repository->findBy(['arTitle' => $data['arTitle']], ['arDate'=>'DESC'],1);
                    foreach ( $article as $a){
                        $dir = 'img/' ;
                        if($form['arLink']->getData()->getMimeType() == 'image/jpeg'){
                            $someNewFilename = $a->getArId().'.jpg';
                        }
                        else{
                            $someNewFilename = $a->getArId().'.png';
                        }
                        $form['arLink']->getData()->move($dir, $someNewFilename);

                        //Met l'url de l'image dans la bd
                        $updateArticle = $em->find('DUT\\Models\\Article',$a->getArId());
                        $updateArticle->setArLink("/img/".$someNewFilename);
                        $em->persist($updateArticle);
                        $em->flush();
                    }
                }
                return $app->redirect('../manageArticles');
            }
        }

        return $app['twig']->render('basedAdminMod.twig', ['form'  => $form->createView()]);
    }

    //Suppresion d'un article et des commentaires associés
    public function displayAdministrationSuppressionById(Application $app, Request $request, $id){
        $em = $app['em'];

        $repository = $em->getRepository('DUT\\Models\\Comment');
        $comments = $repository->findBy(['arId' => $id]);
        if($comments != null){
            $i=0;
            foreach ($comments as $comment){
                $tmp[$i] = $comment->getCoId();
                $i++;
            }
            foreach ($tmp as $a){
                $commentToRemove = $em->find('DUT\\Models\\Comment', $a);
                $em->remove($commentToRemove);
                $em->flush();
            }
        }
        $articleToRemove = $em->find('DUT\\Models\\Article', $id);
        if($articleToRemove->getArLink() != null){
            $parse = explode("/", $articleToRemove->getArLink());
            unlink($parse[1].'/'.$parse[2]);
        }
        $em->remove($articleToRemove);
        $em->flush();

        return $app->redirect('../manageArticles');
    }

    //Gestion des commentaires pour un article choisi
    public function displayAdministrationManageById(Application $app, Request $request, $id){
        $em = $app['em'];

        $repository = $em->getRepository('DUT\\Models\\Comment');
        $comments = $repository->findBy(['arId' => $id]);


        return $app['twig']->render('basedAdminManage.twig', ['comments' => $comments]);
    }

}