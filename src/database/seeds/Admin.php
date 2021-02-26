<?php

use think\migration\Seeder;

class Admin extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $adminData = [
            ['id' => 1, 'username' => 'admin', 'password' => password_hash('123456', PASSWORD_DEFAULT),
                'status' => 1, 'create_time' => time(), 'update_time' => time()]
        ];
        $this->table('admin')->insert($adminData)->save();

        $authRuleData = [
            [
                'id' => 1,
                'pid' => 0,
                'name' => null,
                'title' => '系统设置',
                'icon' => 'fa-cogs',
                'sort' => 9,
                'is_show' => 1,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 2,
                'pid' => 1,
                'name' => 'admin.System/systemInfo',
                'title' => '基本信息',
                'icon' => 'fa-adjust',
                'sort' => 9,
                'is_show' => 1,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 3,
                'pid' => 1,
                'name' => 'admin.System/appInfo',
                'title' => 'APP信息',
                'icon' => 'fa-fax',
                'sort' => 9,
                'is_show' => 1,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 4,
                'pid' => 1,
                'name' => 'admin.System/menuList',
                'title' => '菜单管理',
                'icon' => 'fa-fax',
                'sort' => 9,
                'is_show' => 1,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 5,
                'pid' => 1,
                'name' => 'admin.System/editMenu',
                'title' => '编辑菜单',
                'icon' => 'fa-fax',
                'sort' => 9,
                'is_show' => 2,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 6,
                'pid' => 1,
                'name' => 'admin.System/addMenu',
                'title' => '添加菜单',
                'icon' => 'fa-fax',
                'sort' => 9,
                'is_show' => 2,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 7,
                'pid' => 1,
                'name' => 'admin.System/delMenu',
                'title' => '删除菜单',
                'icon' => 'fa-fax',
                'sort' => 9,
                'is_show' => 2,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 8,
                'pid' => 0,
                'name' => null,
                'title' => '管理员管理',
                'icon' => 'fa-align-justify',
                'sort' => 9,
                'is_show' => 1,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 9,
                'pid' => 8,
                'name' => 'admin.Admin/adminList',
                'title' => '管理员列表',
                'icon' => 'fa-ambulance',
                'sort' => 9,
                'is_show' => 1,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 10,
                'pid' => 8,
                'name' => 'admin.Admin/addAdmin',
                'title' => '添加管理员',
                'icon' => 'fa-american-sign-language-interpreting',
                'sort' => 9,
                'is_show' => 2,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 11,
                'pid' => 8,
                'name' => 'admin.Admin/delAdmin',
                'title' => '删除管理员',
                'icon' => 'fa-500px',
                'sort' => 9,
                'is_show' => 2,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 12,
                'pid' => 8,
                'name' => 'admin.Admin/editAdmin',
                'title' => '编辑管理员',
                'icon' => 'fa-500px',
                'sort' => 9,
                'is_show' => 2,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 13,
                'pid' => 8,
                'name' => 'admin.Admin/wdlEditSelf',
                'title' => '编辑个人信息',
                'icon' => 'fa-500px',
                'sort' => 9,
                'is_show' => 2,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 14,
                'pid' => 8,
                'name' => 'admin.Admin/roleList',
                'title' => '角色管理',
                'icon' => 'fa-anchor',
                'sort' => 9,
                'is_show' => 1,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 15,
                'pid' => 8,
                'name' => 'admin.Admin/addRole',
                'title' => '添加角色',
                'icon' => 'fa-500px',
                'sort' => 9,
                'is_show' => 2,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 16,
                'pid' => 8,
                'name' => 'admin.Admin/delRole',
                'title' => '删除角色',
                'icon' => 'fa-500px',
                'sort' => 9,
                'is_show' => 2,
                'create_time' => time(),
                'update_time' => time(),
            ],
            [
                'id' => 17,
                'pid' => 8,
                'name' => 'admin.Admin/editRole',
                'title' => '编辑角色',
                'icon' => 'fa-500px',
                'sort' => 9,
                'is_show' => 2,
                'create_time' => time(),
                'update_time' => time(),
            ],
        ];
        $this->table('auth_rule')->insert($authRuleData)->save();

        $systemData = [
            ['id' => 1, 'web_name' => 'APP名称', 'copyright' => 'APP名称', 'logo' => '/static/default/no.jpg', 'about_us' => '关于我们', 'create_time' => time(), 'update_time' => time()]
        ];
        $this->table('system')->insert($systemData)->save();
    }
}