<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/13
 * Time: 0:13
 */
namespace app\oauth\controller;

use oauth\OauthServer;
use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {
        $list = Db::connect('oauth')->table('oauth_clients')->where(true)->select();
        $OauthServer = new OauthServer();

        print_r($list);
        die();
        return $this->fetch('index', []);
    }
}