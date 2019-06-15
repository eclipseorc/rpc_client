<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/29
 * Time: 23:50
 */
namespace login;

use inter\ILoginStrategy;

class qqLogin implements ILoginStrategy
{
    public function login()
    {
        echo "qqLogin";
    }
}