<?php
/**
 * Created by PhpStorm.
 * User: plaza
 * Date: 05/01/2017
 * Time: 17:09
 */

namespace DUT\Controllers;

use DUT\Models\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Symfony\Component\Validator\Constraints as Assert;

// Controller gerant l'administration
class AdministrationController
{
    public function __construct()
    {

    }
    //Affichage du form de connextion au mode admin
    public function displayAdministration(Application $app, Request $request, $pw){
            // On créé l'objet form à partir du formBuilder (En passant en param l'objet form)
            $form = $app['form.factory']->createBuilder('form')
                //On ajoute chaque champs de notre form
                ->add('adminUsername', 'text', array(
                    'required' => true,
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
                ))
                ->add('adminPassword', 'password', array(
                    'required' => true))
                ->getForm(); // On récupère l'objet form


            if ('POST' == $request->getMethod()) { // Si on a soumis le formulaire
                $form->bind($request); // On bind les valeurs du POST à notre formulaire
                if ($form->isValid()) { // On teste si les données entrées dans notre formulaire sont valides
                    $data = $form->getData(); //Met les données dans data
                    if ($data['adminUsername'] == 'babas' || $data['adminUsername'] == 'elodie') {
                        if ($data['adminPassword'] == 'azerty') {
                            $app["pw"] = 1;
                        }
                    }
                    return $app->redirect("administration/index");
                }
            }
        return $app['twig']->render('basedAdministration.twig', ['form'  => $form->createView()]);
    }

    //Création d'un nouvelle article - Page principale de la partie admin
    public function displayAdministrationRegister(Application $app, Request $request){
        /*
         * traitement du formulaire de creation d'article
         */

        // On créé l'objet form à partir du formBuilder (En passant en param l'objet form)
        $form = $app['form.factory']->createBuilder('form')
            //On ajoute chaque champs de notre form
            ->add('arTitle', 'text', array(
                'required' => true,
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
            ))
            ->add('arDescription', 'textarea', array(
                'required' => true,
                'attr' => array('cols' => '5', 'rows' => '2'),
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 15)))
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
                'required' => false
            ))
            ->getForm(); // On récupère l'objet form

        if ('POST' == $request->getMethod()){ // Si on a soumis le formulaire
            $form->bind($request); // On bind les valeurs du POST à notre formulaire
            if ($form->isValid() && $form->isSubmitted()){ // On teste si les données entrées dans notre formulaire sont valides

                $data = $form->getData(); //Met les données dans data
                $em = $app['em'];
                $repository = $em->getRepository('DUT\\Models\\Article');

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
                return $app->redirect('index');
            }
        }

        return $app['twig']->render('basedAdminIndex.twig', ['form'  => $form->createView()]);
    }

    //Renvoie les articles
    public function returnArticles(Application $app){
        $em = $app['em'];
        $repository = $em->getRepository('DUT\\Models\\Article');
        $articles = $repository->findby(array(),['arDate'=>'DESC']);
        return $articles;
    }

    // Affichages de tous les articles
    public function displayAdministrationManage(Application $app, Request $request){
        $articles = $this->returnArticles($app);
        return $app['twig']->render('basedAdminManageArticles.twig', ['articles' => $articles]);
    }
}