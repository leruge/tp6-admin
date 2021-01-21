<?php
/**
 * @title 接口路由
 * @author Leruge
 * @email leruge@163.com
 * @qq 305530751
 */

use think\facade\Route;

Route::group('api', function () {
    Route::rule('upload_file', 'api.Tool/uploadFile'); // 上传文件
    Route::rule('send_code', 'api.Tool/sendCode'); // 发送验证码
    Route::rule('version_update', 'api.Tool/versionUpdate'); // 版本更新
    Route::rule('about_us', 'api.Tool/aboutUs'); // 关于我们
});
