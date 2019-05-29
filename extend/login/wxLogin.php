<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/29
 * Time: 23:51
 */
namespace login;

use inter\ILoginStrategy;

class wxLogin implements ILoginStrategy
{
    public function login()
    {
        // TODO: Implement login() method.
        echo "wxLogin";
    }
}