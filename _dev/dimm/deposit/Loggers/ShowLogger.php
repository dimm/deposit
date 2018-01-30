<?php
namespace dimm\deposit\Loggers;

use dimm\deposit\Base\Observerable;
use dimm\deposit\Contracts\IObserver;

/**
 * Show logger. Вывод результатов на экран
 */
class ShowLogger extends Observerable
{
	/**
     * @inheritDoc
     */
    public function afterCalculated(IObserver $subject)
    {
        echo("ID: ".$subject->getDeposit()->getID().'| before amount='.$subject->getDeposit()->getAmount()->value.'| percent='.$subject->getDeposit()->getPercent()->value." | calculated=".$subject->getCalculatedValue().'<br>'."\n");
    }
}
