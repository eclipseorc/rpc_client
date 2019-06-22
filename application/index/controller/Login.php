<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/29
 * Time: 23:52
 */
namespace app\index\controller;

use login\loginContext;
use login\qqLogin;
use login\wxLogin;
use think\App;
use think\Controller;
use think\facade\Request;
use tools\Http;

class Login extends Controller
{
    private $obj = null;

    public function login()
    {
        /*$qqLoginObj= new qqLogin();
        $wxLoginObj= new wxLogin();
        $this->obj = new loginContext($wxLoginObj);
        $this->obj->doLogin();*/
        return $this->fetch('login', []);
    }

    /**
     * oauth登陆
     */
    public function oauthLogin()
    {
        // 准备oauth参数
        $param = [
            'response_type' => 'code',
            'client_id'     => 'abc_test',
            'redirect_uri'  => 'http://client.unbec.com' . url('/index/login/loginCallback'),
            'state'         => md5(time()),
            'scope'         => ''
        ];

        // 重定向到授权页面
        $url    = 'http://oauth.aisark.com/index/oauth/authorize';
        $url    = $url . '?' . http_build_query($param);
        $this->redirect($url);
    }

    public function loginCallback()
    {
        $request = Request::instance();
        // 重定向到该页面时，query_string中待得参数
        $state  = $request->param('state/s', '', '');
        $code   = $request->param('code/s', '', '');
        $domain = 'http://oauth.aisark.com';

        // 构造token请求参数
        $param  = [
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => 'http://client.unbec.com' . url('/index/login/loginCallback'),
            'client_id'     => 'abc_test',
            'client_secret' => 'abc123456'
        ];

        $url    = url('/index/oauth/token');
        $url    = $domain . $url;
        $res    = Http::post($url, $param);
        var_dump($res);
        die();
    }
}