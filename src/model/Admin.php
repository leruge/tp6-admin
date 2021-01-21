<?php
declare (strict_types = 1);

namespace app\model;

use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class Admin extends Model
{
    use SoftDelete;

    public static function onAfterDelete(Model $model)
    {
        Db::startTrans();
        try {
            AuthGroupAccess::destroy(function ($query) use ($model) {
                $query->where('uid', $model['id']);
            });
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollback();
        }
    }

    // status关联数组
    public static array $statusRelateArray = [
        1 => '正常',
        2 => '禁用'
    ];

    // 获取器group_name
    public function getGroupNameAttr($value, $info)
    {
        $groupId = AuthGroupAccess::where('uid', $info['id'])->value('group_id');
        if ($groupId) {
            $groupName = AuthGroup::where('id', $groupId)->value('title');
        } else {
            $groupName = '--';
        }
        return $groupName;
    }

    // 获取器group_id
    public function getGroupIdAttr($value, $info)
    {
        return AuthGroupAccess::where('uid', $info['id'])->value('group_id');
    }
}
