<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');


$app->get('/users', function ($request, $response) {
    return $response->withHeader('Content-type', 'application/json')->write(USERS_LIST);
});

$app->get('/user/{id}', function ($request, $response, array $args) {
    $user = getUser(USERS_LIST, $args['id']);
    if ($user) {
        return $response->withHeader('Content-type', 'application/json')->write($user);
    } else {
        return $response->write('Not found!')->withStatus(404);
    }
});

$app->post('/adduser', function ($request, $response) {
    $user = $request->getParsedBodyParam('user');

    $newUsersList = adduser(USERS_LIST, $user);

    file_put_contents(PATH_TO_USERS, $newUsersList);

    return $response->write(print_r('done'));
});

$app->get('/user/{id}/edit', function ($request, $response, array $args) {
    $id = $args['id'];
    $editUser = getUser(USERS_LIST, $id);
    if ($editUser) {
        return $response->write(print_r($editUser));
    }
    return $response->write('Not found!')->withStatus(404);
});

$app->patch('/user/{id}', function ($request, $response, array $args) {
    $id = $args['id'];
    $editData = file_get_contents('php://input');
    if ($editData) {
        $newUsersList = editUser(USERS_LIST, $editData, $id);
        file_put_contents(PATH_TO_USERS, $newUsersList);
        return $response->write('yes');
    }
    return $response->write('no');
});

$app->delete('/user/{id}', function ($request, $response, array $args) {
    $user = getUser(USERS_LIST, $args['id']);
    if ($user) {
        $newUsersList = deleteUser($args['id'], USERS_LIST);
        file_put_contents(PATH_TO_USERS, $newUsersList);
        return $response->write('Done')->withStatus(200);
    } else {
        return $response->write('Not found!')->withStatus(404);
    }
});

$app->run();
