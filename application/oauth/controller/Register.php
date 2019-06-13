<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/13
 * Time: 0:14
 */
namespace app\oauth\controller;

use think\Controller;
use think\Db;
use think\facade\Request;

class Register extends Controller
{
    /**
     * 注册应用
     * @return mixed
     */
    public function index()
    {
        $request = Request::instance();
        if ($request->isPost()) {
            // 应用id
            $clientId       = $request->param('client_id/s', '', '');
            // 应用私钥
            $clientSecret   = $request->param('client_secret/s', '', '');
            // 应用名称
            $clientName     = $request->param('client_name/s', '', '');
            // 跳转url
            $redirectUrl    = $request->param('redirect_url/s', '', '');

            // 校验参数
            if (empty($clientId)) {
                $this->error('app_id为必填项');
            }
            if (empty($clientSecret)) {
                $this->error('app_secret为必填项');
            }
            if (empty($clientName)) {
                $this->error('应用名称为必填项');
            }

            $data = [
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'client_name'   => $clientName,
                'redirect_uri'  => $redirectUrl,
                'create_time'   => date('Y-m-d H:i:s')
            ];

            $res = Db::connect('oauth')->table('oauth_clients')->insert($data);
            if ($res) {
                $this->success('创建应用成功！');
            } else {
                $this->error('创建应用失败！');
            }
        }
        return $this->fetch('index',[]);
    }
}