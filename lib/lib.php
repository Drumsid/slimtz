<?php

define("LIST_OF_USERS", file_get_contents('users/users.json'));
define("PATH_TO_USER_FILE", __DIR__ . "/../users/users.json");

function getUser($users, $id)
{
    $result = [];
    $users = json_decode($users, true);
    foreach ($users as $user) {
        if ($user['id'] == $id) {
            $result[] = $user;
            return json_encode($result, true);
        }
    }
    return false;
}

function addUser($users, $data)
{
    $users = json_decode($users, true);
    $users[] = ['id' => (int) $data['id'], 'name' => $data['name']];
    return json_encode($users, true);
}

function editUser($users, $data, $id)
{
    $users = json_decode($users, true);
    $data = json_decode($data, true);
    $result = array_map(function ($user) use ($data, $id) {

        if ($user['id'] == $id) {
            $user['name'] = $data['name'];
        }
        return $user;
    }, $users);
    return json_encode($result, true);
}

function deleteUser($delUser, $users)
{
    $users = json_decode($users, true);
    $result = array_filter($users, function ($user) use ($delUser) {
        if ($user['id'] != $delUser) {
            return $user;
        }
    });
    return json_encode($result, true);
}

function validateNewUser($user)
{
    if ($user['id'] == '' || $user['name'] == '' || ! is_integer($user['id'])) {
        return false;
    }
      $result = [];
    foreach ($user as $key => $value) {
        if ($key == 'id' || $key == 'name') {
            $result[$key] = trim(htmlspecialchars($value));
        }
    }
      return $result;
}

function validateUpdateUser($user)
{
    $user = json_decode($user, true);
    $result = [];
    if ($user['name'] == '') {
        return false;
    }
    $result['name'] = trim(htmlspecialchars($user['name']));
    return json_encode($result, true);
}
