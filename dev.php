<?php

$users = [
    ['id' => 1, 'name' => 'Ioan'],
    ['id' => 2, 'name' => 'Petr'],
    ['id' => 3, 'name' => 'Pavel'],
];

file_put_contents('users/users.json', json_encode($users));
