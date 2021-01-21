<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class AuthGroup extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require|number',
        'title|角色名' => 'require|unique:auth_group',
        'rules|权限' => 'require',
        'role_ids|角色' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];

    // 添加场景
    public function sceneAdd()
    {
        return $this->only(['title', 'rules']);
    }

    // 删除场景
    public function sceneDel()
    {
        return $this->only(['role_ids']);
    }

    // 编辑场景
    public function sceneEdit()
    {
        return $this->only(['id', 'title', 'rules']);
    }
}
