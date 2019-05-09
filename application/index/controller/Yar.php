<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/5
 * Time: 23:43
 */
namespace app\index\controller;

use service\ApiBootstrap;
use think\Exception;

class Yar
{
    public function __construct()
    {
        // 初始化控制器
        if (!extension_loaded('yar')) {
            throw new Exception('not support yar');
        }

        $bootstrap = new ApiBootstrap();
        $bootstrap->run();
    }
}