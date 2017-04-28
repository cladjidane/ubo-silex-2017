<?php
require __DIR__.'/bootstrap.php';

$app = new Silex\Application();
$app['debug'] = true;

// Twig Extension
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/templates',
));

// Assets
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1',
    'assets.version_format' => '%s?version=%s',
    'assets.named_packages' => array(
        'css' => array('version' => 'css', 'base_path' => '/css'),
        'images' => array('base_urls' => array('http://demo.cloudimg.io/s/resize/300/sample.li/')),
    ),
));

// Routes
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.twig', array(
               'title' => "Hello World",
               'colors' => array("Pomme", "green", "yellow"),
            ));
});

/*
$app->get('/contact', function () use ($app) {
    return $app['twig']->render('contact.twig', array(
               'title' => "Contact",
            ));
});
*/
$app->mount('/contact', include __DIR__.'/controllers/contact.php');

return $app;
