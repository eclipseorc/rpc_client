<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/6/14
 * Time: 11:26
 */
namespace app\oauth\controller;

use cache\cache;
use OAuth2\Request;
use OAuth2\Response;
use think\Controller;

class Oauth extends Controller
{
    public function authorize()
    {
        $request        = Request::createFromGlobals();
        $clientId       = $request->query('client_id');
        $state          = $request->query('state');
        $redirectUri    = $request->query('redirect_uri');

        if (empty(cookie('unbec'))) {
            $assign = [
                'title' => '登陆'
            ];

            // 缓存记录请求数据
            $res = cache::set('oauth:client_id', $clientId, 300);
            cache::set('oauth:state', $state, 300);
            cache::set('oauth:redirect_uri', $redirectUri, 300);

            return $this->fetch('login', $assign);
        } else {

            if ($this->request->isPost()) {
                $this->_authorize();
            }

            $assign = [
                'title'         => '授权',
                'client_id'     => $clientId,
                'state'         => $state,
                'redirect_uri'  => $redirectUri
            ];
            return $this->fetch('authorize', $assign);
        }
    }

    public function doLogin()
    {
        $clientId   = cache::get('oauth:client_id');
        $state      = cache::get('oauth:state');
        $redirectUri= cache::get('oauth:redirect_uri');

        if (empty($clientId) || empty($state) || empty($redirectUri)) {
            $this->error('请求超时，请重新登陆');
        }

        cookie('unbec', 'unbec');
        $param  = [
            'client_id'     => $clientId,
            'state'         => $state,
            'redirect_uri'  => $redirectUri
        ];
        $request    = \think\facade\Request::instance();
        $domain     = $request->domain();
        $url        = url('oauth/oauth/authorize');
        $url        = $domain . $url . '?' . http_build_query($param);
        $this->redirect($url);
    }

    private function _authorize()
    {
        $request        = Request::createFromGlobals();
        $isAuthorized   = $request->request('is_authorized');
        $userId         = $request->request('user_id');

        if ($isAuthorized == '1') {
            $response   = new Response();
            $oauth      = new \controller\Oauth();
            if (!$oauth->server->validateAuthorizeRequest($request, $response)) {
                $response->send();
                exit();
            }

            // 重新赋值authorize
            $isAuthorized = true;
            $oauth->server->handleAuthorizeRequest($request, $response, $isAuthorized, $userId);
            $response->send();
            exit();
        }
    }

    public function token()
    {
        $request = Request::createFromGlobals();
        $oauth = new \controller\Oauth();
        $oauth->server->handleTokenRequest($request)->send();
    }
}