<?php
$route_config = [
    'default_module'     => 'index',
    'default_controller' => 'Index',
    'default_action'     => 'index',

    'customMap' => [
        '/abc/aa' => ['app\\index\\IndexController', 'index'],
    ]
];