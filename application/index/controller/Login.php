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

class Login extends Controller
{
    private $obj = null;

    public function login()
    {
        $qqLoginObj= new qqLogin();
        $wxLoginObj= new wxLogin();
        $this->obj = new loginContext($wxLoginObj);
        $this->obj->doLogin();
    }
}