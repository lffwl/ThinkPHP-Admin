<?php
// 事件定义文件
return [
    'bind' => [
        //绑定登录事件
        'AdminLogin' => 'app\event\AdminLogin',
    ],

    'listen' => [
        'AppInit' => [],
        'HttpRun' => [],
        'HttpEnd' => [],
        'LogLevel' => [],
        'LogWrite' => [],
        //登录事件监听
        'AdminLogin' => ['app\event\AdminLogin'],
    ],

    'subscribe' => [
        //登录事件订阅
        'app\event\AdminLogin',
    ],
];
