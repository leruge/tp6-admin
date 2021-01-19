<?php
namespace app\controller\admin;


use app\model\AuthRule;
use app\model\System;
use leruge\tool\Auth;

class Index
{
    // 无权限页面
    public function wdlNoAuth()
    {
        return view('no_auth');
    }

    // 框架
    public function wdlFrame()
    {
        return view('frame');
    }

    // 框架首页
    public function wdlIndex()
    {
        return view('index');
    }

    // 左侧菜单列表
    public function wdlLeftMenuList()
    {
        $adminId = session('adminId');
        // 首页信息
        $homeInfo = [
            'title' => '首页',
            'href' => (string)url('admin.Index/wdlIndex')
        ];

        // logo信息
        $systemInfo = System::order('id', 'desc')->find();
        $logoInfo = [
            'title' => $systemInfo['web_name'],
            'image' => $systemInfo['logo'],
            'href' => ''
        ];
        $auth = new Auth();
        $userRuleIdArray = $auth->userRuleIdList($adminId);
        $menuList = AuthRule::field(['id', 'pid', 'title', 'icon', 'name' => 'href'])
            ->where('id', 'in', $userRuleIdArray)
            ->where('is_show', 1)->order(['sort' => 'asc', 'id' => 'asc'])->withAttr([
                'target' => function ()
                {
                    return '_self';
                },
                'icon' => function ($value)
                {
                    return 'fa ' . $value;
                }
            ])->select()->append(['target'])->toArray();
        $menuList = $this->buildMenuList(0, $menuList);
        // 菜单信息
        $menuInfo = $menuList;
        $info = [
            'homeInfo' => $homeInfo,
            'logoInfo' => $logoInfo,
            'menuInfo' => $menuInfo
        ];
        return json($info);
    }

    // 组装菜单
    private function buildMenuList($pid, $menuList)
    {
        $treeList = [];
        foreach ($menuList as $v) {
            if ($pid == $v['pid']) {
                $v['href'] = $v['href'] ? (string)url($v['href']) : null;
                $node = $v;
                $child = $this->buildMenuList($v['id'], $menuList);
                if (!empty($child)) {
                    $node['child'] = $child;
                }
                $treeList[] = $node;
            }
        }
        return $treeList;
    }
}