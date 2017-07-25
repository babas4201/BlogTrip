<?php

require __DIR__ . '/../vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

/* Formulaire */
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Symfony\Component\Validator\Constraints as Assert;

$app = new Silex\Application();
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

/**
 * DEPENDANCES
 */
$app['connection'] = [
    'driver' => 'pdo_mysql',
    'host' => '127.0.0.1',
    'user' => 'root',
    'password' => '',
    'dbname' => 'silex_project'
];

$app['doctrine_config'] = Setup::createYAMLMetadataConfiguration([__DIR__ . '/../config'], true);
$app['em'] = function ($app) {
    return EntityManager::create($app['connection'], $app['doctrine_config']);
};

/**
 * Installation Twig
 */
$app->register(new Silex\Provider\TwigServiceProvider(),['twig.path' => dirname(__DIR__).'/src/Views',]);


/**
 * Formulaire Twig
 */
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

/**
 * Securité Admin
 * Test
 */
/*
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'pattern' => '^/admin/',
            'form' => array('login_path' => '/admin', 'check_path' => '/admin/index'),
            'users' => array(
                'admin' => array('ROLE_ADMIN', '$2y$10$3i9/lVd8UOFIJ6PAMFt8gu3/r5g0qeCJvoSlLCsvMTythye19F77a'),
            ),
        ),
    )
));
*/

/**
 * Appel a twig
 * Tous les liens aux appels des controllers en methode get et post pour les formulaires
 */

$app ->get ('/accueil' , 'DUT\\Controllers\\HomePageController::display');

$app ->get ('/articles' , 'DUT\\Controllers\\ArticleController::displayArticles');

$app ->get ('/article/{id}' , 'DUT\\Controllers\\CommentArticleController::displayArticle');
$app ->post ('/article/{id}' , 'DUT\\Controllers\\CommentArticleController::displayArticle');

$app ->get ('/admin' , 'DUT\\Controllers\\AdministrationController::displayAdministration');
$app ->post ('/admin' , 'DUT\\Controllers\\AdministrationController::displayAdministration');

$app ->get ('/photos' , 'DUT\\Controllers\\PhotosController::displayPhotos');

$app ->get ('/administration/index', 'DUT\\Controllers\\AdministrationController::displayAdministrationRegister');
$app ->post ('/administration/index', 'DUT\\Controllers\\AdministrationController::displayAdministrationRegister');

$app ->get ('/administration/manageArticles', 'DUT\\Controllers\\AdministrationController::displayAdministrationManage');


$app ->get ('/administration/mod/{id}', 'DUT\\Controllers\\AdministrationManageController::displayAdministrationModificationById');
$app ->post ('/administration/mod/{id}', 'DUT\\Controllers\\AdministrationManageController::displayAdministrationModificationById');

$app ->get ('/administration/supp/{id}', 'DUT\\Controllers\\AdministrationManageController::displayAdministrationSuppressionById');
$app ->get ('/administration/manage/{id}', 'DUT\\Controllers\\AdministrationManageController::displayAdministrationManageById');

$app ->get ('/administration/comment/mod/{id}/{idArticle}', 'DUT\\Controllers\\AdministrationCommentController::AdministrationModComment');
$app ->post ('/administration/comment/mod/{id}/{idArticle}', 'DUT\\Controllers\\AdministrationCommentController::AdministrationModComment');

$app ->get ('/administration/comment/supp/{id}/{idArticle}', 'DUT\\Controllers\\AdministrationCommentController::AdministrationSuppComment');


//Active le mode debug
//$app['debug'] = true;

//Lancement de l'appli
$app->run();
