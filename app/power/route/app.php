<?php

use think\facade\Route;

//跨域
Route::group(function () {
    //管理员登录
    Route::post('Admin/login', 'Admin/login');

    //登录 jwt验证
    Route::group(function () {

        //管理员详情
        Route::get('Admin/info', 'Admin/info');

        //权限验证
        Route::group(function () {
            //管理员模块
            Route::group('Admin', function () {
                Route::get('index', 'index');
                Route::get('info', 'info');
                Route::post('create', 'create');
                Route::post('/:id/update', 'update');
                Route::delete('/:id/delete', 'delete');
                Route::post('/:id/setRole', 'setRole');
            })->prefix('Admin/')->pattern(['id' => '\d+']);

            //角色模块
            Route::group('Role', function () {
                Route::get('index', 'index');
                Route::post('create', 'create');
                Route::post('/:id/update', 'update');
                Route::delete('/:id/delete', 'delete');
                Route::post('/:id/setPower', 'setPower');
            })->prefix('Role/')->pattern(['id' => '\d+']);

            //权限模块
            Route::group('Power', function () {
                Route::get('index', 'index');
                Route::post('create', 'create');
                Route::post('/:id/update', 'update');
                Route::delete('/:id/delete', 'delete');
            })->prefix('Power/')->pattern(['id' => '\d+']);

        })->middleware(\app\middleware\AdminCheckPower::class);

    })->middleware(thans\jwt\middleware\JWTAuth::class);

});
