<?php
namespace dimm\deposit\Contracts;

use dimm\deposit\Entities\Deposit\Contracts\IDepositEntity;
/**
 * Интерфес для объектов рассчета комиссий, процентов депозита, налога и т.п.
 */
interface ICalculator
{

    /**
     * Возвращает результат рассчета
     * @param IDepositEntity $DepositEntity Объект депозита, пр которому будет произведен рассчет
     * @return double
     */
    public function getCalculated(IDepositEntity $DepositEntity);
}
