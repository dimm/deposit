<?php
namespace dimm\deposit\Base;

use dimm\deposit\Contracts\IObserverable;
use dimm\deposit\Contracts\IObserver;
/**
 * inheritdoc
 */
class Observerable implements IObserverable
{
    /**
     * Метод, который будет вызван на срабатывание события afterCalculate
     * @param  IObserver
     */
    public function afterCalculated(IObserver $subject)
    {

    }
}
