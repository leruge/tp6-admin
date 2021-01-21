<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Admin extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require|number',
        'username|用户名' => 'require',
        'password|密码' => 'require',
        'group_id|角色' => 'require',
        'status|状态' => 'in:1,2',
        'admin_ids|管理员' => 'require',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];

    // 登录场景
    public function sceneLogin()
    {
        return $this->only(['username', 'password']);
    }

    // 添加场景
    public function sceneAdd()
    {
        return $this->only(['group_id', 'username', 'password', 'status'])
            ->remove('password', 'require')
            ->remove('group_id', 'require')
            ->append('username', 'unique:admin');
    }

    // 编辑场景
    public function sceneEdit()
    {
        return $this->only(['id', 'group_id', 'password', 'status'])
            ->remove('group_id', 'require')
            ->remove('password', 'require');
    }
}
