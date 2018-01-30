<?php
namespace dimm\deposit\Calculators;

use dimm\deposit\Contracts\ICalculator;
use dimm\deposit\Entities\Deposit\Contracts\IDepositEntity;

/**
 * Ежемесячное начисление процентов по депозиту
 */
class CapitalizePerMonth implements ICalculator
{

    /**
     * @inheritdoc
     */
    public function getCalculated(IDepositEntity $DepositEntity)
    {
        $month_percent = ($DepositEntity->getPercent()->value / 12) / 100;
        return $DepositEntity->getAmount()->value * $month_percent;
    }
}
