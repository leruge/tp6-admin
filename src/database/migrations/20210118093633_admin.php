<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Admin extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('admin', ['collation' => 'utf8mb4_bin', 'comment' => '管理员表'])
            ->addColumn('username', 'string', ['null' => true, 'comment' => '用户名'])
            ->addColumn('password', 'string', ['default' => password_hash('123456', PASSWORD_DEFAULT), 'comment' => '密码'])
            ->addColumn('status', 'integer', ['default' => 1, 'comment' => '1正常；2禁用'])
            ->addColumn('create_time', 'integer', ['null' => true])
            ->addColumn('update_time', 'integer', ['null' => true])
            ->addColumn('delete_time', 'integer', ['null' => true])
            ->create();

        $this->table('auth_group', ['collation' => 'utf8mb4_bin', 'comment' => '角色表'])
            ->addColumn('title', 'string', ['null' => true, 'comment' => '角色名称'])
            ->addColumn('rules', 'string', ['null' => true, 'comment' => '权限，多个使用逗号分隔'])
            ->addColumn('create_time', 'integer', ['null' => true])
            ->addColumn('update_time', 'integer', ['null' => true])
            ->addColumn('delete_time', 'integer', ['null' => true])
            ->create();

        $this->table('auth_group_access', ['collation' => 'utf8mb4_bin', 'comment' => '用户角色表'])
            ->addColumn('uid', 'integer', ['null' => true, 'comment' => '用户ID'])
            ->addColumn('group_id', 'integer', ['null' => true, 'comment' => '角色ID'])
            ->addColumn('create_time', 'integer', ['null' => true])
            ->addColumn('update_time', 'integer', ['null' => true])
            ->addColumn('delete_time', 'integer', ['null' => true])
            ->create();

        $this->table('auth_rule', ['collation' => 'utf8mb4_bin', 'comment' => '权限表'])
            ->addColumn('pid', 'integer', ['default' => 0, 'comment' => '上级ID'])
            ->addColumn('name', 'string', ['null' => true, 'comment' => '权限规则'])
            ->addColumn('title', 'string', ['null' => true, 'comment' => '权限规则中文'])
            ->addColumn('icon', 'string', ['null' => true, 'comment' => '图标'])
            ->addColumn('sort', 'integer', ['default' => 9, 'comment' => '排序'])
            ->addColumn('is_show', 'integer', ['default' => 1, 'comment' => '1显示；2隐藏'])
            ->addColumn('create_time', 'integer', ['null' => true])
            ->addColumn('update_time', 'integer', ['null' => true])
            ->addColumn('delete_time', 'integer', ['null' => true])
            ->create();

        $this->table('system', ['collation' => 'utf8mb4_bin', 'comment' => '系统表'])
            ->addColumn('web_name', 'string', ['default' => null, 'comment' => 'APP名称'])
            ->addColumn('copyright', 'string', ['default' => null, 'comment' => '版权信息'])
            ->addColumn('logo', 'string', ['null' => true, 'comment' => 'logo'])
            ->addColumn('about_us', 'text', ['length' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG, 'comment' => '关于我们'])
            ->addColumn('android_version', 'string', ['default' => '0.0.0', 'comment' => '安卓版本'])
            ->addColumn('android_url', 'string', ['null' => true, 'comment' => '安卓地址'])
            ->addColumn('ios_version', 'string', ['default' => '0.0.0', 'comment' => '苹果版本'])
            ->addColumn('ios_url', 'string', ['null' => true, 'comment' => '苹果地址'])
            ->addColumn('update_desc', 'string', ['null' => true, 'comment' => '更新描述'])
            ->addColumn('up_ios', 'string', ['default' => '0.0.0', 'comment' => '苹果上架版本'])
            ->addColumn('create_time', 'integer', ['null' => true])
            ->addColumn('update_time', 'integer', ['null' => true])
            ->addColumn('delete_time', 'integer', ['null' => true])
            ->create();

        $this->table('store', ['collation' => 'utf8mb4_bin', 'comment' => '文件库'])
            ->addColumn('url', 'string', ['default' => null, 'comment' => '文件地址'])
            ->addColumn('hash', 'string', ['default' => null, 'comment' => 'hash值'])
            ->addColumn('create_time', 'integer', ['null' => true])
            ->addColumn('update_time', 'integer', ['null' => true])
            ->addColumn('delete_time', 'integer', ['null' => true])
            ->create();
    }
}
