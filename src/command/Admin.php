<?php
declare (strict_types = 1);

namespace leruge\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Admin extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('leruge:admin')
            ->setDescription('生成后台文件');
    }

    protected function execute(Input $input, Output $output)
    {
        // 复制route文件
        $routeSourcePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'route';
        $routeTargetPath = root_path() . 'route';
        $this->copyFile($routeSourcePath, $routeTargetPath);

        // 复制控制器
        $controllerSourcePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'controller';
        $controllerTargetPath = root_path() . 'app' . DIRECTORY_SEPARATOR . 'controller';
        $this->copyFile($controllerSourcePath, $controllerTargetPath);

        // 复制视图
        $viewSourcePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'view';
        $viewTargetPath = root_path() . 'view';
        $this->copyFile($viewSourcePath, $viewTargetPath);

        // 复制静态资源
        $staticSourcePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'static';
        $staticTargetPath = root_path() . 'public' . DIRECTORY_SEPARATOR . 'static';
        $this->copyFile($staticSourcePath, $staticTargetPath);

        // 复制迁移文件和种子
        $migrateSourcePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'database';
        $migrateTargetPath = root_path() . 'database';
        $this->copyFile($migrateSourcePath, $migrateTargetPath);

        // 复制模型
        $modelSourcePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'model';
        $modelTargetPath = root_path() . 'app' . DIRECTORY_SEPARATOR . 'model';
        $this->copyFile($modelSourcePath, $modelTargetPath);

        // 复制验证器
        $validateSourcePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'validate';
        $validateTargetPath = root_path() . 'app' . DIRECTORY_SEPARATOR . 'validate';
        $this->copyFile($validateSourcePath, $validateTargetPath);

        // 复制中间件
        $middlewareSourcePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'middleware';
        $middlewareTargetPath = root_path() . 'app' . DIRECTORY_SEPARATOR . 'middleware';
        $this->copyFile($middlewareSourcePath, $middlewareTargetPath);

        // 成功
        $output->writeln('后台文件生成成功');
    }

    // 复制文件到指定目录
    private function copyFile($sourcePath, $targetPath)
    {
        $sourcePath = $sourcePath . DIRECTORY_SEPARATOR;
        $targetPath = $targetPath . DIRECTORY_SEPARATOR;
        if (!file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
        }
        $handle = scandir($sourcePath);
        foreach ($handle as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (is_dir($sourcePath . $file)) {
                if (!file_exists($targetPath . $file)) {
                    mkdir($targetPath . $file, 0777, true);
                }
                $this->copyFile($sourcePath . $file, $targetPath . $file);
            } else {
                copy($sourcePath . $file, $targetPath . $file);
            }
        }
    }
}
