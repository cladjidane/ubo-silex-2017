<?php
require __DIR__.'/bootstrap.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'path'     => __DIR__.'/app.db',
        'host'     => 'localhost',  
        'dbname'   => 'db_silex',
        'user'     => 'dba_silex',
        'password' => '#fMB7UBh8tDuq',
        'port'     => '3306'
    ),
));

// Twig Extension
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/templates',
));

use Silex\Provider\FormServiceProvider;

$app->register(new FormServiceProvider());

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));


$app->match('/form', function (Request $request) use ($app) {
    // some default data for when the form is displayed the first time
    $data = array(
        'name' => 'Your name',
        'email' => 'Your email',
    );

    $form = $app['form.factory']->createBuilder(FormType::class, $data)
        ->add('name')
        ->add('email')
        ->add('billing_plan', ChoiceType::class, array(
            'choices' => array('free' => 1, 'small business' => 2, 'corporate' => 3),
            'expanded' => true,
        ))
        ->add('submit', SubmitType::class, [
            'label' => 'Save',
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        // do something with the data

        // redirect somewhere
        return $app->redirect('merci');
    }

    // display the form
    return $app['twig']->render('form.twig', array('form' => $form->createView()));
});



// Routes

$app->get('/merci', function () use ($app) {

    return $app['twig']->render('merci.twig', array(
       'title' => 'Merci',
    ));
});

$app->get('/', function () use ($app) {

    return $app['twig']->render('index.twig', array(
       'title' => 'Home',
    ));
});

$app->get('/article/{id}', function ($id = 1) use ($app) {

    $sql = "SELECT * FROM article WHERE id = ?";
    $post = $app['db']->fetchAssoc($sql, array((int) $id));

    //$sql = "UPDATE posts SET value = ? WHERE id = ?";
    //$app['dbs']['mysql_write']->executeUpdate($sql, array('newValue', (int) $id));

    return $app['twig']->render('article.twig', array(
       'title' => $post['title'],
       'desc' => $post['desc'],
    ));
});


$app->get('/contact', function () use ($app) {
    return $app['twig']->render('contact.twig', array(
               'title' => "Hello World",
            ));
});

// Variables globales
$app["twig"]->addGlobal("titremenu", array(
    array('href' => '#accueil', 'title' => 'Home'),
    array('href' => '#présentation', 'title' => 'Présentation'),
    array('href' => '#donnees', 'title' => 'Données'),
    array('href' => '#contact', 'title' => 'Contact'),
));


return $app;
