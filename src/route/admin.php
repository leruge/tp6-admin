<?php
/**
 * @title 后台路由
 * @author Leruge
 * @email leruge@163.com
 * @qq 305530751
 */
use think\facade\Route;

Route::group('admin', function () {
    Route::rule('/', 'admin.Login/login'); // 登录
    Route::rule('no_auth', 'admin.Index/wdlNoAuth'); // 无权限页面

    // 需要登录
    Route::group(function () {
        Route::rule('frame', 'admin.Index/wdlFrame');
    })->middleware([\app\middleware\AdminLoginCheck::class]);
})->allowCrossDomain();
