<?php
declare (strict_types = 1);

namespace app\controller\admin;

use app\model\AuthGroup;
use app\model\AuthGroupAccess;
use app\model\AuthRule;
use think\facade\Db;
use think\facade\View;

class Admin
{
    // 管理员列表
    public function adminList()
    {
        if (request()->isPost()) {
            $params = request()->only(['username', 'limit', 'page']);
            $where = $order = [];
            if (isset($params['username']) && $params['username']) {
                $where[] = ['username', 'like', '%' . $params['username'] . '%'];
            }
            $order = [
                'create_time' => 'desc',
                'id' => 'desc'
            ];
            $list = \app\model\Admin::where($where)->order($order)->paginate($params['limit'], false, ['query' => $params])
                ->append(['group_name']);
            $res = [
                'list' => $list->items(),
                'count' => $list->total()
            ];
            result($res, 0);
        }
        return view();
    }

    // 添加管理员
    public function addAdmin()
    {
        if (request()->isAjax()) {
            $params = request()->only(['group_id', 'username', 'password', 'status']);
            $validate = (new \app\validate\Admin())->scene('add');
            if (!$validate->check($params)) {
                result(null, 0, $validate->getError());
            } else {
                if (!empty($params['password'])) {
                    $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
                } else {
                    $params['password'] = password_hash('123456', PASSWORD_DEFAULT);
                }
                Db::startTrans();
                try {
                    $addDataAdmin = [];
                    foreach ($params as $k => $v) {
                        if ($v) {
                            $addDataAdmin[$k] = $v;
                        }
                    }
                    $adminInfo = \app\model\Admin::create($addDataAdmin);
                    if (!empty($params['group_id'])) {
                        $addDataAuthAccess = [
                            'uid' => $adminInfo['id'],
                            'group_id' => $params['group_id']
                        ];
                        AuthGroupAccess::create($addDataAuthAccess);
                    }
                    Db::commit();
                } catch (\Throwable $e) {
                    Db::rollback();
                    result(null, $e->getCode(), $e->getMessage());
                }
                result(null, 1, '添加成功');
            }
        }
        $groupList = AuthGroup::select();
        $viewData = [
            'groupList' => $groupList
        ];
        View::assign($viewData);
        return view();
    }

    // 删除管理员
    public function delAdmin()
    {
        $params = request()->only(['admin_ids']);
        $adminIdArray = explode(',', $params['admin_ids']);
        $adminIdArray = array_diff($adminIdArray, config('admin.auth.super_id_array') ?: []);
        sort($adminIdArray);
        if (!$adminIdArray) {
            result(null, 0, '不能删除管理员');
        }
        Db::startTrans();
        try {
            \app\model\Admin::destroy($adminIdArray);
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollback();
            result(null, 0, $e->getMessage());
        }
        result('', 1, '删除成功');
    }

    // 编辑管理员
    public function editAdmin()
    {
        if (request()->isAjax()) {
            $params = request()->only(['id', 'group_id', 'password', 'status']);
            $validate = (new \app\validate\Admin())->scene('edit');
            if (!$validate->check($params)) {
                result(null, 0, $validate->getError());
            }
            $adminId = session('adminId');
            if (!empty($params['password'])) {
                $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
            }
            // 如果是自己则不允许禁用自己
            if ($adminId == $params['id'] && !empty($params['status']) && $params['status'] == 2) {
                result(null, 0, '不允许禁用自己');
            }
            $superIdArray = config('admin.auth.super_id_array') ?: [];
            if (in_array($params['id'], $superIdArray) && !empty($params['status']) && $params['status'] == 2) {
                result(null, 0, '不允许禁用超级管理员');
            }
            Db::startTrans();
            try {
                $updateDataAdmin = [];
                foreach ($params as $k => $v) {
                    if ($v) {
                        $updateDataAdmin[$k] = $v;
                    }
                }
                $adminInfo = \app\model\Admin::update($updateDataAdmin);
                if(!empty($params['group_id'])) {
                    $addDataAuthAccess = [
                        'uid' => $adminInfo['id'],
                        'group_id' => $params['group_id']
                    ];
                    $authGroupAccessInfo = AuthGroupAccess::getByUid($adminInfo['id']);
                    if ($authGroupAccessInfo) {
                        AuthGroupAccess::update($addDataAuthAccess, ['uid' => $adminInfo['uid']]);
                    } else {
                        AuthGroupAccess::create($addDataAuthAccess);
                    }
                }
                Db::commit();
            } catch (\Throwable $e) {
                Db::rollback();
                result(null, $e->getCode(), $e->getMessage());
            }
            result(null, 1, '设置成功');
        }
        $groupList = AuthGroup::order('id', 'desc')->select();
        $adminInfo = \app\model\Admin::find(input('id'))->append(['group_id']);
        $viewData = [
            'groupList' => $groupList,
            'adminInfo' => $adminInfo
        ];
        View::assign($viewData);
        return view();
    }

    // 编辑自己信息
    public function wdlEditSelf()
    {
        if (request()->isAjax()) {
            $params = request()->only(['id', 'password']);
            $validate = (new \app\validate\Admin())->scene('edit');
            if (!$validate->check($params)) {
                result(null, 0, $validate->getError());
            }
            if (!empty($params['password'])) {
                $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
            }
            Db::startTrans();
            try {
                $updateDataAdmin = [];
                foreach ($params as $k => $v) {
                    if ($v) {
                        $updateDataAdmin[$k] = $v;
                    }
                }
                \app\model\Admin::update($updateDataAdmin);
                Db::commit();
            } catch (\Throwable $e) {
                Db::rollback();
                result(null, $e->getCode(), $e->getMessage());
            }
            result(null, 1, '设置成功');
        }
        $groupList = AuthGroup::order('id', 'desc')->select();
        $adminId = session('adminId');
        $adminInfo = \app\model\Admin::find($adminId)->append(['group_id']);
        $viewData = [
            'groupList' => $groupList,
            'adminInfo' => $adminInfo
        ];
        View::assign($viewData);
        return view('edit_self');
    }

    // 角色列表
    public function roleList()
    {
        if (request()->isPost()) {
            $params = request()->only(['limit', 'page']);
            $list = AuthGroup::paginate($params['limit'], false, ['query' => $params]);
            $res = [
                'list' => $list->items(),
                'count' => $list->total()
            ];
            result($res, 0);
        }
        return view();
    }

    // 添加角色
    public function addRole()
    {
        if (request()->isPost()) {
            $params = request()->only(['title', 'rules']);
            $validate = (new \app\validate\AuthGroup())->scene('add');
            if (!$validate->check($params)) {
                result(null, 0, $validate->getError());
            } else {
                Db::startTrans();
                try {
                    $addDataAuthGroup = [];
                    foreach ($params as $k => $v) {
                        if ($v) {
                            $addDataAuthGroup[$k] = $v;
                        }
                    }
                    AuthGroup::create($addDataAuthGroup);
                    Db::commit();
                } catch (\Throwable $e) {
                    Db::rollback();
                    result(null, $e->getCode(), $e->getMessage());
                }
                result(null, 1);
            }
        }
        $oneMenuList = AuthRule::where('pid', 0)->order(['sort' => 'asc', 'id' => 'asc'])->withAttr([
            'spread' => function () {
                return true;
            },
            'children' => function ($value, $info) {
                $menuList = AuthRule::where('pid', $info['id'])->order(['sort' => 'asc', 'id' => 'asc'])->select();
                return $menuList;
            }
        ])->select()->append(['spread', 'children']);
        $viewData = [
            'oneMenuList' => $oneMenuList
        ];
        View::assign($viewData);
        return view();
    }

    // 删除角色
    public function delRole()
    {
        $params = request()->only(['role_ids']);
        $roleIdArray = explode(',', $params['role_ids'] ??= '');
        if (!$roleIdArray) {
            result(null, 0, '至少删除一个');
        }
        Db::startTrans();
        try {
            AuthGroup::destroy($roleIdArray);
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollback();
            result(null, $e->getCode(), $e->getMessage());
        }
        result(null, 1, '删除成功');
    }

    // 编辑角色
    public function editRole()
    {
        if (request()->isPost()) {
            $params = request()->only(['id', 'title', 'rules']);
            $validate = (new \app\validate\AuthGroup())->scene('edit');
            if (!$validate->check($params)) {
                result(null, 0, $validate->getError());
            } else {
                Db::startTrans();
                try {
                    $updateDataAuthGroup = [];
                    foreach ($params as $k => $v) {
                        if ($v) {
                            $updateDataAuthGroup[$k] = $v;
                        }
                    }
                    AuthGroup::update($updateDataAuthGroup);
                    Db::commit();
                } catch (\Throwable $e) {
                    Db::rollback();
                    result(null, $e->getCode(), $e->getMessage());
                }
                result(null, 1, '设置成功');
            }
        }
        $roleInfo = AuthGroup::where('id', input('id'))->withAttr([
            'rules' => function ($value) {
                $oneRules = AuthRule::where(['pid' => 0])->column('id');
                $rules = explode(',', $value);
                return implode(',', array_diff($rules, $oneRules));
            }
        ])->find();
        $oneMenuList = AuthRule::field(['id', 'title'])->where('pid', 0)->order('sort')->withAttr([
            'spread' => function () {
                return true;
            },
            'children' => function ($value, $info) {
                $menuList = AuthRule::field(['id', 'title'])->where('pid', $info['id'])->order('sort')->select();
                return $menuList;
            }
        ])->select()->append(['spread', 'children']);
        $viewData = [
            'roleInfo' => $roleInfo,
            'rules' => $roleInfo['rules'] ?? '',
            'oneMenuList' => $oneMenuList
        ];
        View::assign($viewData);
        return view();
    }
}
