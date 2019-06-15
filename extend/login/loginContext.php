<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/29
 * Time: 23:59
 */
namespace login;

use inter\ILoginStrategy;

class loginContext
{
    private $obj;

    public function __construct(ILoginStrategy $strategy)
    {
        $this->obj = $strategy;
    }

    public function doLogin()
    {
        $this->obj->login();
    }
}