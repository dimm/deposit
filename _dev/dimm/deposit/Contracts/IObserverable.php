<?php
namespace dimm\deposit\Contracts;
/**
 * Интерфейс который должен быть реализован классом-подписчиком на события
 */
interface IObserverable
{
	/**
     * Метод, который будет вызван на срабатывание события afterCalculate
     * @param  IObserver
     */
    public function afterCalculated(IObserver $subject);
}
