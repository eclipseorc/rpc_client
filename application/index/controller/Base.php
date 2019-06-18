<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/6/14
 * Time: 11:12
 */
namespace app\index\controller;

use controller\Oauth;
use OAuth2\Request;
use think\Controller;

class Base extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $oauth  = new Oauth();
        $bool   = $oauth->server->validateAuthorizeRequest(Request::createFromGlobals());
        if (!$bool) {
            exit('无权访问该接口');
        }
    }
}