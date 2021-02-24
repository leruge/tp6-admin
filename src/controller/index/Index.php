<?php
declare (strict_types = 1);

namespace app\controller\index;

use app\model\System;
use tekintian\QRcode;
use think\facade\View;

class Index
{
    // 首页
    public function index()
    {
        $systemInfo = System::order('id', 'desc')->find();
        $viewData = [
            'logo' => $systemInfo['logo'],
        ];
        View::assign($viewData);
        return view();
    }

    // 下载页面
    public function down()
    {
        $memberId = input('member_id', 0);
        $system = System::order('id', 'desc')->find();
        $text = (string)url('index.Index/down', ['member_id' => $memberId], true, true);
        $outFile = root_path() . 'public/static/uploads/' . $memberId . '.png';
        $url = config('extra.app_url') . '/static/uploads/' . $memberId . '.png';
        if (!file_exists($outFile)) {
            try {
                QRcode::png($text, $outFile, 10);
            } catch (\Throwable $e) {

            }
        }
        $viewData = [
            'android_url' => $system['android_url'],
            'ios_url' => $system['ios_url'],
            'web_name' => $system['web_name'],
            'logo' => $system['logo'],
            'memberId' => $memberId,
            'qr_url' => $url,
            'urlScheme' => 'weinihao'
        ];
        View::assign($viewData);
        return view();
    }

    // 隐私协议
    public function agreement()
    {
        return view();
    }
}
