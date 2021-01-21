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
    Route::rule('logout', 'admin.Login/logout'); // 注销
    Route::rule('no_auth', 'admin.Index/wdlNoAuth'); // 无权限页面

    // 需要登录
    Route::group(function () {
        Route::rule('frame', 'admin.Index/wdlFrame'); // 框架
        Route::rule('index', 'admin.Index/wdlIndex'); // 框架首页
        Route::rule('left_menu_list', 'admin.Index/wdlLeftMenuList'); // 菜单列表

        // 系统设置
        Route::rule('system_info', 'admin.System/systemInfo'); // 系统设置
        Route::rule('app_info', 'admin.System/appInfo'); // APP设置
        Route::rule('menu_list', 'admin.System/menuList'); // 菜单列表
        Route::rule('edit_menu', 'admin.System/editMenu'); // 编辑菜单
        Route::rule('add_menu', 'admin.System/addMenu'); // 添加菜单
        Route::rule('del_menu', 'admin.System/delMenu'); // 删除菜单

        // 管理员管理
        Route::rule('admin_list', 'admin.Admin/adminList'); // 管理员列表
        Route::rule('add_admin', 'admin.Admin/addAdmin'); // 添加管理员
        Route::rule('del_admin', 'admin.Admin/delAdmin'); // 删除管理员
        Route::rule('edit_admin', 'admin.Admin/editAdmin'); // 编辑管理员
        Route::rule('edit_self', 'admin.Admin/wdlEditSelf'); // 编辑个人信息
        Route::rule('role_list', 'admin.Admin/roleList'); // 角色管理
        Route::rule('add_role', 'admin.Admin/addRole'); // 添加角色
        Route::rule('del_role', 'admin.Admin/delRole'); // 删除角色
        Route::rule('edit_role', 'admin.Admin/editRole'); // 编辑角色
    })->middleware([\app\middleware\AdminLoginCheck::class]);
})->allowCrossDomain();
