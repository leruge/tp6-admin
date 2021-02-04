<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class System extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require',
        'web_name|APP名称' => 'require',
        'copyright|版权信息' => 'require',
        'logo|LOGO' => 'require',
        'about_us|关于我们' => 'require',
        'android_version|安卓版本号' => 'require',
        'android_url|安卓地址' => 'require',
        'ios_version|苹果版本号' => 'require',
        'ios_url|苹果地址' => 'require',
        'update_desc|更新描述' => 'require',
        'up_ios|上架版本' => 'require',
        'sort|排序' => 'integer',
        'is_show|显示' => 'in:1,2',
        'pid|上级' => 'integer',
        'title|名称' => 'require',
        'name|菜单规则' => 'require',
        'icon|图标' => 'require'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];

    public function sceneEdit()
    {
        return $this->only(['id', 'web_name', 'copyright', 'logo', 'about_us',
            'android_version', 'android_url', 'ios_version', 'ios_url', 'update_desc', 'up_ios',
            'sort', 'is_show', 'pid', 'title', 'name', 'icon'])
            ->remove('web_name', 'require')
            ->remove('copyright', 'require')
            ->remove('logo', 'require')
            ->remove('about_us', 'require')
            ->remove('android_version', 'require')
            ->remove('android_url', 'require')
            ->remove('ios_version', 'require')
            ->remove('ios_url', 'require')
            ->remove('update_desc', 'require')
            ->remove('up_ios', 'require')
            ->remove('title', 'require')
            ->remove('name', 'require')
            ->remove('icon', 'require');
    }

    public function sceneAdd()
    {
        return $this->only(['pid', 'title', 'name', 'icon', 'sort', 'is_show'])
            ->remove('name', 'require')
            ->append('name', 'unique:auth_rule');
    }
}
