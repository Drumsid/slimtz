<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Headers: *');
// header('Access-Control-Allow-Methods: *');
// header('Access-Control-Allow-Credentials: true');

// $app->add(function ($request, $handler) {
//     $response = $handler->handle($request);
//     return $response
//             ->withHeader('Access-Control-Allow-Origin', '*')
//             ->withHeader('Access-Control-Allow-Headers', '*')
//             ->withHeader('Access-Control-Allow-Credentials', true)
//             ->withHeader('Access-Control-Allow-Methods', '*');
// });

$app->get('/', function ($request, $response, array $args) {
    return $response->write('Simple API!');
});

$app->get('/users', function ($request, $response) {
    return $response->withHeader('Content-type', 'application/json')->write(LIST_OF_USERS)->withStatus(200);
});

$app->get('/user/{id}', function ($request, $response, array $args) {
    $user = getUser(LIST_OF_USERS, $args['id']);
    if ($user) {
        return $response->withHeader('Content-type', 'application/json')->write($user)->withStatus(200);
    } else {
        return $response->write('User is not found!')->withStatus(404);
    }
});

$app->post('/adduser', function ($request, $response) {
    $user = $request->getParsedBodyParam('user');

    $validateUser = validateNewUser($user);
    if ($validateUser) {
        $newUsersList = addUser(LIST_OF_USERS, $validateUser);
        file_put_contents(PATH_TO_USER_FILE, $newUsersList);
        return $response->write('User was added successfully!')->withStatus(200);
    }
    return $response->write('Something went wrong check your details!')->withStatus(400);
});

$app->get('/user/{id}/edit', function ($request, $response, array $args) {
    $id = $args['id'];
    $editUser = getUser(LIST_OF_USERS, $id);
    if ($editUser) {
        return $response->withHeader('Content-type', 'application/json')->write($editUser)->withStatus(200);
    }
    return $response->write('User is not found!')->withStatus(404);
});

$app->patch('/user/{id}', function ($request, $response, array $args) {
    $id = $args['id'];
    $editData = file_get_contents('php://input');
    $validateData = validateUpdateUser($editData);
    if ($validateData) {
        $newUsersList = editUser(LIST_OF_USERS, $validateData, $id);
        file_put_contents(PATH_TO_USER_FILE, $newUsersList);
        return $response->write('Data was updated successfully!')->withStatus(200);
    }
    return $response->write('Something went wrong check your details!')->withStatus(400);
});

$app->delete('/user/{id}', function ($request, $response, array $args) {
    $user = getUser(LIST_OF_USERS, $args['id']);
    if ($user) {
        $newUsersList = deleteUser($args['id'], LIST_OF_USERS);
        file_put_contents(PATH_TO_USER_FILE, $newUsersList);
        return $response->write('You successfully deleted the user!')->withStatus(200);
    } else {
        return $response->write('User is not found!')->withStatus(404);
    }
});

$app->run();
