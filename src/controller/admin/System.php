<?php

namespace app\controller\admin;

class System
{
    // 基本信息
    public function systemInfo()
    {
        if (request()->isAjax()) {
            $params = request()->only(['id', 'web_name', 'copyright', 'logo', 'about_us',
                'android_version', 'android_url', 'ios_version', 'ios_url', 'update_desc', 'up_ios']);
            $validate = (new \app\validate\System())->scene('edit');
            if (!$validate->check($params)) {
                result(null, 0, $validate->getError());
            }
            Db::startTrans();
            try {
                $updateDataSystem = [];
                foreach ($params as $k => $v) {
                    if ($v) {
                        $updateDataSystem[$k] = $v;
                    }
                }
                \app\model\System::update($updateDataSystem);
                Db::commit();
            } catch (\Throwable $e) {
                Db::rollback();
                result(null, $e->getCode(), $e->getMessage());
            }
            result(null, 1, '设置成功');
        }
        $systemInfo = \app\model\System::order('id', 'desc')->find();
        View::assign('systemInfo', $systemInfo);
        return view();
    }

    // APP信息
    public function appInfo()
    {
        $systemInfo = \app\model\System::order('id', 'desc')->find();
        View::assign('systemInfo', $systemInfo);
        return view();
    }

    // 菜单列表
    public function menuList()
    {
        if (request()->isPost()) {
            $menuList = AuthRule::order(['sort' => 'asc', 'id' => 'asc'])->select()->toArray();
            $menuList = recursion($menuList, 0);
            foreach ($menuList as $k => $v) {
                $menuList[$k]['title'] = $v['title'];
            }
            result(['list' => $menuList, 'count' => count($menuList)], 0, '获取分页数据成功');
        }
        return view();
    }

    // 编辑菜单
    public function editMenu()
    {
        if (request()->isAjax()) {
            $params = request()->only(['id', 'sort', 'is_show', 'pid', 'title', 'name', 'icon']);
            $validate = (new \app\validate\System())->scene('edit');
            if (!$validate->check($params)) {
                result(null, 0, $validate->getError());
            }
            Db::startTrans();
            try {
                $updateDataMenu = [];
                foreach ($params as $k => $v) {
                    if ($v) {
                        $updateDataMenu[$k] = $v;
                    }
                }
                \app\model\AuthRule::update($updateDataMenu);
                Db::commit();
            } catch (\Throwable $e) {
                Db::rollback();
                result(null, $e->getCode(), $e->getMessage());
            }
            result(null, 1, '设置成功');
        }
        $oneMenuList = AuthRule::where(['pid' => 0])->order('sort', 'asc')->select();
        $menuInfo = AuthRule::find(input('id'));
        $viewData = [
            'oneMenuList' => $oneMenuList,
            'menuInfo' => $menuInfo
        ];
        View::assign($viewData);
        return view();
    }

    // 添加菜单
    public function addMenu()
    {
        if (request()->isAjax()) {
            $params = request()->only(['pid', 'title', 'name', 'icon', 'sort', 'is_show']);
            $validate = (new \app\validate\System())->scene('add');
            if (!$validate->check($params)) {
                result(null, 0, $validate->getError());
            } else {
                Db::startTrans();
                try {
                    $addDataMenu = [];
                    foreach ($params as $k => $v) {
                        if ($v) {
                            $addDataMenu[$k] = $v;
                        }
                    }
                    AuthRule::create($addDataMenu);
                    Db::commit();
                } catch (\Throwable $e) {
                    Db::rollback();
                    result('', 0, $e->getMessage());
                }
                result(null, 1, '操作成功');
            }
        }
        $oneMenuList = AuthRule::where(['pid' => 0])->order('sort', 'asc')->select();
        $viewData = [
            'oneMenuList' => $oneMenuList
        ];
        View::assign($viewData);
        return view();
    }

    // 删除菜单
    public function delMenu()
    {
        Db::startTrans();
        try {
            AuthRule::destroy(input('id'));
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollback();
            result(null, 0, $e->getMessage());
        }
        result(null, 1, '操作成功');
    }
}
