<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Symfony\Component\HttpFoundation\Request;


Request::enableHttpMethodParameterOverride();

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
// $app->register(new Silex\Provider\SecurityServiceProvider(), array(
//    'security.firewalls' => // see below
// );
$app->register(new Silex\Provider\SessionServiceProvider());
// $app->register(new Silex\Provider\TranslationServiceProvider(), array(
// 	'translator.messages' => array(),
// ));

/*Implémentation de doctrine*/
$app->register(new Silex\Provider\DoctrineServiceProvider());
//
//var_dump($app);
$app['dao.user'] =function ($app) {
    return new SilexApi\UserDao($app['db']);
};


//var_dump($app);
$app['dao.task'] =function ($app) {
    return new SilexApi\TaskDao($app['db']);
};

// Register JSON data decoder for JSON requests
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});


$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...

    return $twig;
});

return $app;
