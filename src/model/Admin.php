<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class Admin extends Model
{
    use SoftDelete;

    public static $statusRelateArray = [
        1 => '正常',
        2 => '禁用'
    ];
}
