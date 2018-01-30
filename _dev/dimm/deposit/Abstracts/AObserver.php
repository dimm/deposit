<?php
namespace dimm\deposit\Abstracts;

use dimm\deposit\Contracts\IObserver;
use dimm\deposit\Contracts\IObserverable;

/**
 *  Класс для реализации возможности оповещения подпищикам о событях
 *
 */
abstract class AObserver implements IObserver
{
    /**
     * Коллекция подписчиков
     * @var array
     */
    protected $listeners = [];

    /**
     * Добавление подписчика
     * @param  string        $event    название события, на которое будет вызван подписчик
     * @param  IObserverable $instance Подписчик
     */
    public function attach($event, IObserverable $instance)
    {
        $this->listeners[$event][] = $instance;
        return $this;
    }

    /**
     * Оповещение подписчиков о наступлении события
     * @param  string $event Название события
     */
    public function trigger($event)
    {
        if (isset($this->listeners[$event])) {
            foreach ($this->listeners[$event] as $instance) {
                $instance->$event($this);
            }
        }
    }

}
