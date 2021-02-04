<?php
declare (strict_types = 1);

namespace app\controller\api;

use app\model\Region;
use app\model\Store;
use app\model\System;
use app\validate\File;
use think\facade\Filesystem;
use think\facade\Validate;

/**
 * @title 工具方法
 */
class Tool
{
    /**
     * @title 上传文件
     * @url /api/upload_file
     * @method post
     *
     * @param name:file type:file require:1 desc:文件，暂时支持
     * @param name:type type:integer require:0 default:1 desc:1图片；2非图片
     *
     * @return url:图片地址
     */
    public function uploadFile()
    {
        $params = request()->only(['file', 'type']);
        $params['file'] = request()->file('file');
        $params['type'] ??= 1;
        if ($params['type'] == 1) {
            $validate = Validate::rule([
                'file|文件' => 'require|image',
                'type|文件类型' => 'in:1,2',
            ]);
        } elseif ($params['type'] == 2) {
            $validate = Validate::rule([
                'file|文件' => 'require|file',
                'type|文件类型' => 'in:1,2',
            ]);
        } else {
            result(null, 0, '未知的文件类型');
        }
        if (is_array($params['file'])) {
            foreach ($params['file'] as $file) {
                if (!$validate->check(['file' => $file])) {
                    result(null, 0, $validate->getError());
                }
            }
            $saveNameArray = [];
            foreach ($params['file'] as $file) {
                $hash = $file->hash('md5');
                $hashInfo = Store::getByHash($hash);
                if ($hashInfo) {
                    $saveNameArray[] = handle_get_pic($hashInfo['url'], 3);
                } else {
                    $saveName = Filesystem::putFile('', $file);
                    $url = str_replace('\\', '/', '/static/uploads/' . $saveName);
                    Store::create([
                        'url' => $url,
                        'hash' => $hash
                    ]);
                    $saveNameArray[] = handle_get_pic($url, 3);
                }
            }
            result(['url' => $saveNameArray], 1, '上传成功');
        } else {
            if (!$validate->check($params)) {
                result(null, 0, $validate->getError());
            }
            $file = $params['file'];
            $hash = $file->hash('md5');
            $hashInfo = Store::getByHash($hash);
            if ($hashInfo) {
                $url = handle_get_pic($hashInfo['url'], 3);
            } else {
                $saveName = Filesystem::putFile('', $file);
                $url = str_replace('\\', '/', '/static/uploads/' . $saveName);
                Store::create([
                    'url' => $url,
                    'hash' => $hash
                ]);
                $url = handle_get_pic($url, 3);
            }
            result(['url' => $url], 1, '上传成功');
        }
    }

    /**
     * @title 版本更新
     * @url /api/version_update
     * @method post
     *
     * @param name:system_type type:string require:1 desc:传android或者ios
     * @param name:version type:string require:1 desc:版本号
     *
     * @return version_info:版本信息@!
     * @version_info is_update:是否更新更新；2不更新 download_url:下载地址 update_desc:更新描述 up_ios:苹果上架版本 is_ios_up_hide:1要隐藏；2不要
     */
    public function versionUpdate()
    {
        $params = [
            'system_type' => input('system_type', 'android'),
            'version' => input('version', '0.0.0')
        ];
        $systemInfo = System::order('id', 'desc')->find();
        if ($params['system_type'] == 'android') {
            $res = version_compare($params['version'], $systemInfo['android_version']);
            $url = $systemInfo['android_url'];
        } elseif ($params['system_type'] == 'ios') {
            $res = version_compare($params['version'], $systemInfo['ios_version']);
            $url = $systemInfo['ios_url'];
        } else {
            result(null, 0, '未知的系统');
        }
        $isUpdate = $res == -1 ? 1 : 2;
        $updateDesc = $systemInfo['update_desc'];
        $upIos = $systemInfo['up_ios'];
        $isIosUpHide = 2;
        if ($params['system_type'] == 'ios') {
            if ($params['version'] == $systemInfo['up_ios']) {
                $isIosUpHide = 1;
            }
        }

        $versionInfo = [
            'version_info' => [
                'is_update' => $isUpdate,
                'download_url' => $url,
                'update_desc' => $updateDesc,
                'up_ios' => $upIos,
                'is_ios_up_hide' => $isIosUpHide
            ]
        ];
        result($versionInfo, 1, '获取信息成功');
    }

    /**
     * @title 关于我们
     * @url /api/about_us
     * @method post
     *
     * @return info:关于我们@!
     * @info title:app名称 about_us:内容 logo:logo
     */
    public function aboutUs()
    {
        $systemInfo = System::order('id', 'desc')->find();
        $info = [
            'title' => $systemInfo['web_name'],
            'logo' => $systemInfo['logo'],
            'about_us' => $systemInfo['about_us']
        ];
        result($info, 1, '获取信息成功');
    }

    /**
     * @title 发送验证码
     * @url /api/send_code
     * @method post
     *
     * @param name:phone type:string require:1 desc:手机号
     * @param name:type type:string require:0 default:1 desc:1不验证手机；2验证手机必须存在；3验证手机不能存在
     * @param name:dev type:integer require:0 default:1 desc:1正式环境；2测试环境
     */
    public function sendCode()
    {
        $params = request()->only(['phone', 'type', 'dev']);
        $params['type'] ??= 1;
        $params['dev'] ??= 1;
        $validate = [];
        if ($params['type'] == 1) {
            $validate = Validate::rule([
                'phone|手机' => 'require|mobile'
            ]);
        } elseif ($params['type'] == 2) {
            $validate = Validate::rule([
                'phone|手机' => 'require|mobile'
            ]);
        } elseif ($params['type'] == 3) {
            $validate = Validate::rule([
                'phone|手机' => 'require|mobile|unique:member'
            ]);
        } else {
            result(null, 0, '未知的验证类型');
        }
        if (!$validate->check($params)) {
            result(null, 0, $validate->getError());
        } else {
            if (cache('code' . $params['phone'])) {
                if ($params['dev'] != 1) {
                    result(null, 0, config('extra.sms.expire') . 's内不允许重复发送' . cache('code' . $params['phone']));
                } else {
                    result(null, 0, config('extra.sms.expire') . 's内不允许重复发送');
                }
            }

            $code = mt_rand(1000, 9999);
            cache('code' . $params['phone'], $code, config('extra.sms.expire'));
            // debug关闭并且dev是1才发短信
            if ($params['dev'] == 1) {
                $sendResult = send_code($params['phone'], (string)$code);
                if ($sendResult['code'] == 1) {
                    result(null, 1, $sendResult['msg']);
                } else {
                    cache('code' . $params['phone'], null);
                    result(null, 0, $sendResult['msg']);
                }
            } else {
                result(null, 1, '发送成功' . $code);
            }
        }
    }
}
