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
})->allowCrossDomain();
