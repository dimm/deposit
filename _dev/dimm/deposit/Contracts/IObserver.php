<?php
namespace dimm\deposit\Contracts;

/**
 * Интерфейс для реализации оповещения подписчиков о наступлении события
 * @todo deatach, has и т.д. А также другие события по необходимости
 */
interface IObserver
{
    /**
     * Событие, которое будет вызвано после проведения рассчета
     */
    const EVENT_AFTER_CALCUlATED = 'afterCalculated';

    /**
     * Добавление подписчика
     * @param  string        $event    название события, на которое будет вызван подписчик
     * @param  IObserverable $instance Подписчик
     */
    public function attach($event, IObserverable $instance);

    /**
     * Оповещение подписчиков о наступлении события
     * @param  string $event Название события
     */
    public function trigger($event);
}
