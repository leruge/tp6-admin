<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class AuthRule extends Model
{
    use SoftDelete;

    public static $isShowRelateArray = [
        1 => '显示',
        2 => '隐藏'
    ];
}
