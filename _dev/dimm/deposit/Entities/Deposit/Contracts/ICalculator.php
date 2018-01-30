<?php
namespace dimm\deposit\Entities\Deposit\Contracts;

/**
 * Интерфес для объектов рассчета комиссий, процентов депозита, налога и т.п.
 */
interface ICalculator
{

    /**
     * Возвращает результат рассчета
     * @param IDepositEntity $depositEntity Объект депозита, пр которому будет произведен рассчет
     * @return double
     */
    public function getCalculated(IDepositEntity $deposit);
}
