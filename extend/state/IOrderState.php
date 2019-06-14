<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/6/3
 * Time: 17:38
 */
namespace state;

interface IOrderState
{
    public function getState($orderId);
    /**
     * 动作
     * @return mixed
     */
    public function action();

    /**
     * 打印数据
     * @return mixed
     */
    public function doPrint();
}