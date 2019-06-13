<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/31
 * Time: 0:24
 */
namespace inter;

class Weather implements IObservable
{
    private $observers = array();

    public function registerObserver($obj)
    {
        // TODO: Implement registerObserver() method.
        $this->observers[] = $obj;
    }

    public function removeObserver($obj)
    {
        // TODO: Implement removeObserver() method.
        array_keys($obj, $this->observers);
        unset($this->observers[$obj]);
    }

    public function notifyObservers()
    {
        // TODO: Implement notifyObservers() method.
        if (!empty($this->observers) && is_array($this->observers)) {
            $this->observers;
        }
    }

    private function notify()
    {

    }
}