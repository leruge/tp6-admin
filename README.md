# thinkphp6后台扩展

## 功能
1. 系统设置
1. 管理员管理
1. 权限
1. 迁移和种子
1. 错误页面、下载页面、首页

## 使用说明
1. 安装后台扩展 `composer require leruge/tp6-admin=dev-master`
1. 安装封装函数 `composer require leruge/tp6-helper=dev-main`
1. 生成后台所需文件 `php think leruge:admin`
1. 执行完后会在config目录生成extra.php和admin.php配置文件
1. 执行完成以后会生成各种模型、控制器等文件
1. 开启开发模式env，配置app_url全局url
1. 删除static下的.gitignore文件
1. 配置数据库，编码设置utf8mb4
1. 执行迁移 `php think migrate:run`
1. 种子填充（不允许多次执行） `php think seed:run`
1. 后台登录状态使用session，所有需要开启session中间件，用的name是adminId
1. 配置磁盘filesystem.php如下
```
'customer' => [
    'type' => 'local',
    'root' => app()->getRootPath() . 'public' . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR . 'uploads',
]
```
1. 实现send_code($phone, $code)，返回结果1是发送成功