<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/5/30
 * Time: 16:41
 */
namespace login;

class login
{
    private $strategy;

    public function __construct(ILogin $obj)
    {
        $this->strategy = $obj;
    }

    public function doLogin()
    {
        $this->strategy->login();
    }
}