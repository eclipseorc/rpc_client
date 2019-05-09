<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/2/19
 * Time: 11:15
 */
namespace service;

abstract class AbstractBootstrap
{
    public function __construct()
    {
        define('APP_MODE', 'Yar');
    }

    public function run($argv = null)
    {
        $handleMethod = 'exec' . APP_MODE;
        $this->{$handleMethod}($argv);
    }
}