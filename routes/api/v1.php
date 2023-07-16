<?php

/** @var \Laravel\Lumen\Routing\Router $router */
$router->get('a', function (){
    dd(\App\Models\User::create([
        'full_name' => 'mmmeysam',
    ]));
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->post('',  'API\V1\UsersController@store');
        $router->put('',  'API\V1\UsersController@updateInfo');
        $router->put('change-password',  'API\V1\UsersController@updatePassword');
        $router->delete('',  'API\V1\UsersController@delete');
        $router->get('',  'API\V1\UsersController@index');

    });
});
