<?php
namespace dimm\deposit\Calculators;

use dimm\deposit\Contracts\ICalculator;
use dimm\deposit\Entities\Deposit\Contracts\IDepositEntity;
use dimm\deposit\Helpers\DateHelper;

/**
 * Непосредственно реализация подсчета комисии.
 *
 * Комиссия начисляется раз в месяц. Зависит от суммы и даты начала депозита.
 *
 *
 * @todo Валидация градации
 */

class GridCommission implements ICalculator
{
    /**
     * Градация процентов может задаваться через вызов
     * dimm\deposit\Calculators\GridCommission::$grid = [...];
     * Где элементы массива
     * 1-й - сумма депозита от
     * 2-й - сумма депозита до
     * 3-й - процент
     * min - минимальная сумма комиссии (опционально)
     * max - максимальная сумма комиссии (опционально)
     *
     * Значение inf - без ограничения (infinity)
     * @var array
     */
    public static $grid = [
        [0, 1000, 5, 'min' => 50.0],
        [1000, 10000, 6],
        [10000, 'inf', 7, 'max' => 5000.0],
    ];
    /**
     * @inheritdoc
     */
    public function getCalculated(IDepositEntity $DepositEntity)
    {
        $amount = $DepositEntity->getAmount()->value;
        /**
         * @todo sort of ranges
         */
        $defined_range = reset(self::$grid);

        foreach (self::$grid as $key => $range) {
            if ($amount >= $range[0] && ($amount < $range[1] || $range[1] == 'inf')) {
                $defined_range = $range;
            }

        }
        $percent_value = $defined_range[2];
        
        //Регистрация в прошлом месяце
        $start_at = $DepositEntity->getPeriod()->getStartAt()->format('Y-m-d');

        if (DateHelper::getCountMonth(new \DateTime($start_at), new \DateTime(date('Y-m-d'))) == 0) 
        {
            $registred_days = DateHelper::getCountDays(new \DateTime($start_at), new \DateTime(date('Y-m-d')));
            $percent_value  = $percent_value * $registred_days / 31; //Вот тут подумать

            //сбрасываем минимум
            $defined_range['min'] = 0;
        }

        $percents = $amount * $percent_value / 100;

        if (isset($defined_range['min']) && $percents < $defined_range['min']) {
            $percents = $defined_range['min'];
        }

        if (isset($defined_range['max']) && $percents > $defined_range['max']) {
            $percents = $defined_range['max'];
        }

        return $percents;
    }
}
