<?php

define("USERS_LIST", file_get_contents('users/users.json'));
define("PATH_TO_USERS", __DIR__ . "/../users/users.json");

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

function adduser($users, $data)
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
