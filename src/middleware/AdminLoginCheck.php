<?php
declare (strict_types = 1);

namespace app\middleware;

use app\model\Admin;

class AdminLoginCheck
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $adminId = session('adminId');
        if (!$adminId) {
            return $this->offline();
        }
        $adminInfo = Admin::find($adminId);

        if (!$adminInfo) {
            return $this->offline();
        }

        if ($adminInfo['status'] != 1) {
            return $this->offline();
        }

        return $this->online($request, $next);
    }

    // 掉线处理
    private function offline()
    {
        session(null);
        return redirect((string)url('admin.Login/login'));
    }

    // 正常处理
    private function online($request, $next)
    {
        $isAccess = false;
        $dispatch = request()->controller() . '/' . request()->action();
        $auth = (new \leruge\tool\Auth());
        if ($auth->check($dispatch, session('adminId'))) {
            $isAccess = true;
        }

        // wdl开头的方法不需要权限
        if (substr(request()->action(), 0, 3) == 'wdl') {
            $isAccess = true;
        }

        if ($isAccess) {
            return $next($request);
        } else {
            return $this->noAuth();
        }
    }

    // 无权限
    private function noAuth()
    {
        if (request()->isAjax()) {
            return json(['code' => 0, 'msg' => '无权限']);
        } else {
            return redirect((string)url('admin.Index/wdlNoAuth'), 403);
        }
    }
}
