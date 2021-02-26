<?php
/**
 * @title 前端路由
 * @author Leruge
 * @email leruge@163.com
 * @qq 305530751
 */

use think\facade\Route;

Route::rule('down', 'index.Index/down'); // 下载页面
Route::rule('/', 'index.Index/index'); // 官网页面
Route::rule('agreement', 'index.Index/agreement'); // 隐私协议
