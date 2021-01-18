<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class System extends Model
{
    use SoftDelete;

    // 修改器logo
    public function setLogoAttr($value)
    {
        return handle_set_pic($value, 1);
    }

    // 获取器logo
    public function getLogoAttr($value)
    {
        return handle_get_pic($value, 3);
    }

    // 修改器about_us
    public function setAboutUsAttr($value)
    {
        return handle_set_content($value);
    }

    // 获取器about_us
    public function getAboutUsAttr($value)
    {
        return handle_get_content($value);
    }
}
