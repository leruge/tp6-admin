<?php
declare (strict_types = 1);

namespace app\command;

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
        $this->setName('publish:admin')
            ->setDescription('生成后台文件');
    }

    protected function execute(Input $input, Output $output)
    {
        // 复制route文件
        $this->copyRoute();
    }

    // 复制路由文件
    private function copyRoute()
    {
        $sourcePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'route' . DIRECTORY_SEPARATOR;
        $targetPath = root_path() . 'route' . DIRECTORY_SEPARATOR;
        $sourceHandle = dir($sourcePath);
        while ($file = $sourceHandle->read()) {
            if ($file != '.' && $file != '..') {
                if (is_file($sourcePath . $file)) {
                    copy($sourcePath . $file, $targetPath . $file);
                }
            }
        }
    }
}
