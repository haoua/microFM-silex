<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use SilexApi\Task;
use SilexApi\TaskDao;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->before(function ($request) {
    $request->getSession()->start();
});

$app->match('/', function (Request $request) use ($app) {
    $data = array(
        'username' => 'Username',
        'password' => 'Password',
    );

    $form = $app['form.factory']->createBuilder(FormType::class, $data)
        ->add('username')
        ->add('password', PasswordType::class)
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        if (isset($data["username"]) && isset($data["password"])) {
            $user = $app['dao.user']->find($data["username"]);
            if ($user == 0) {
               return $app->redirect('/');
            }else{
                $login = $app['dao.user']->login($user->id, $data["username"], $data["password"]);
                if ($login == 1) {
                    $app['session']->set('userdata', array('id' => $user->id, 'username' => $data["username"]));
                    return $app->redirect('/tasks');
                }else{
                    return $app->redirect('/?prb=1');
                }
            }
        }
    }


    return $app['twig']->render('index.html.twig', array('form' => $form->createView()));
})
->bind('homepage');

// Delete user
$app->delete('/tasks/delete/{id}', function ($id, Request $request) use ($app) {
    $app['dao.task']->delete($id);

    return $app->redirect('/tasks');
});


$app->match('/tasks', function (Request $request) use ($app) {
    $userConnected = $app['session']->get('userdata');

    $data = array(
        'Tache' => '',
        'id_user' => $userConnected['id']
    );

    $form = $app['form.factory']->createBuilder(FormType::class, $data)
        ->add('Tache')
        ->add('id_user', HiddenType::class)
        ->getForm();

    $tasks = $app['dao.task']->findAll($userConnected["id"]);
    $number = count($tasks);
    return $app['twig']->render('task.html.twig', array("username" => $userConnected["username"], "tasks" => $tasks, "number" => $number, 'form' => $form->createView()));

})->before(function (Request $request) use ($app){
    $userConnected = $app['session']->get('userdata');
    if (!$userConnected) {
        return $app->redirect('/');    

    }
});


$app->get('/logout', function () use ($app) {
    $app['session']->remove('userdata');
    return $app->redirect('/');
});


$app->post('/tasks/create', function (Request $request) use ($app) {
    $req = $request->request->all();
    $userConnected = $app['session']->get('userdata');

    if (isset($req["form"]["Tache"])) {
        $task = new Task();
        $task->setName($req["form"]["Tache"]);
        $task->setUserId($req["form"]["id_user"]);
        $app['dao.task']->save($task);
        return $app->redirect('/tasks');
     }
});



$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
