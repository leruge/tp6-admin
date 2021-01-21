<?php
declare (strict_types = 1);

namespace app\model;

use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class AuthGroup extends Model
{
    use SoftDelete;

    public static function onAfterDelete(Model $model)
    {
        Db::startTrans();
        try {
            AuthGroupAccess::destroy(function ($query) use ($model) {
                $query->where('group_id', $model['id']);
            });
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollback();
        }
    }
}
