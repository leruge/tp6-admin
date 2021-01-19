<?php
namespace app\controller\admin;


class Index
{
    // 无权限页面
    public function wdlNoAuth()
    {
        return view('no_auth');
    }

    // 框架
    public function frame()
    {
        return view();
    }
}