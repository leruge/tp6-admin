<?php


namespace leruge\tool;


use app\model\AuthGroup;
use app\model\AuthGroupAccess;
use app\model\AuthRule;

class Auth
{
    protected $config = [
        'auth_on' => true, // 认证开关
        'super_id_array' => [], // 超级管理员
    ];

    public $authRule = AuthRule::class;
    public $authGroup = AuthGroup::class;
    public $authGroupAccess = AuthGroupAccess::class;

    public function __construct()
    {
        if (config('admin.auth')) {
            $this->config = array_merge($this->config, config('admin.auth'));
        }
    }

    /**
     * @title 检查权限
     *
     * @param string|array $name 验证的权限，字符串是单条权限，多条权限使用数组
     * @param integer $uid 验证用户ID
     * @param string $relation or满足一条则通过；and全部满足才能通过
     *
     * @return boolean 通过验证返回true，不通过返回false
     */
    public function check($name, $uid, $relation = 'and')
    {
        if (!$this->config['auth_on']) {
            return true;
        }

        if (in_array($uid, $this->config['super_id_array'])) {
            return true;
        }

        $authList = $this->authList($uid);

        // 用户需要校验的权限
        if (is_string($name)) {
            $name = [$name];
        }

        foreach ($name as $k1 => $v1) {
            $name[$k1] = strtolower($v1);
        }
        foreach ($authList as $k2 => $v2) {
            $authList[$k2] = strtolower($v2);
        }

        $diff = array_diff($name, $authList);
        $intersect = array_intersect($name, $authList);
        if ($relation == 'or' && $intersect) {
            return true;
        }
        if ($relation == 'and' && !$diff) {
            return true;
        }
        return false;
    }

    /**
     * @title 用户的权限列表
     *
     * @param integer $uid 用户ID
     *
     * @return array 权限规则列表
     */
    public function authList($uid)
    {
        // 获取用户权限ID数组
        $ruleIdArray = $this->userRuleIdList($uid);

        return (new $this->authRule)->where('id', 'in', $ruleIdArray)->column('name');
    }

    /**
     * @title 用户的权限ID列表
     *
     * @param integer $uid 用户ID
     *
     * @return array 权限ID列表
     */
    public function userRuleIdList($uid)
    {
        if (!$this->config['auth_on']) {
            $ruleIdArray = (new $this->authRule)->column('id');
        } else {
            if (in_array($uid, $this->config['super_id_array'])) {
                $ruleIdArray = (new $this->authRule)->column('id');
            } else {
                $groupIdArray = (new $this->authGroupAccess)->where('uid', $uid)->column('group_id');
                $where[] = ['id', 'in', $groupIdArray];
                $rulesList = (new $this->authGroup)->where($where)->column('rules');
                $ruleIdArray = [];
                foreach ($rulesList as $k => $v) {
                    $ruleIdArray = array_merge($ruleIdArray, explode(',', trim($v, ',')));
                }
                $ruleIdArray = array_unique($ruleIdArray);
                $ruleIdArray = array_filter($ruleIdArray);
                sort($ruleIdArray);
            }
        }
        return $ruleIdArray;
    }
}