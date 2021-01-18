<?php

namespace leruge;
use leruge\command\Admin;
use think\Service;

class AdminService extends Service
{
    public function boot()
    {
        $this->commands([
            'leruge:admin' => Admin::class,
        ]);
    }
}