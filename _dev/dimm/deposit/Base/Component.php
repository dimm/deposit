<?php
namespace dimm\deposit\Base;

use dimm\deposit\Abstracts\AObserver;
use dimm\deposit\Contracts\ICalculator;
use dimm\deposit\Entities\Deposit\Contracts\IDepositEntity;

/**
 * Компонент работы с депозитом (начисление, сняти комиссии и т.д. по необходимости)
 */
abstract class Component extends AObserver
{
    /**
     *     Операция снятия коммиссии
     */
    const TYPE_COMMISSION = 0;
    /**
     *     Операция начисления процентов по депозиту
     */
   
    const TYPE_Deposit = 1;

    /**
     *     Статус активного депозита
     */
    const STATUS_ACTIVE = 1;

    /**
     *     Текущий депозит
     * @var IDepositEntity
     */
    protected $deposit;

    /**
     * Модель рассчета
     * @var ICalculator
     */
    protected $calculator;

    /**
     * Значение рассчитаных процентов
     * @var [type]
     */
    protected $calculated_value;

    /**
     *     Возвращает рассчитанную сумму процента
     * @return float
     */
    public function getCalculatedValue()
    {
        return $this->calculated_value;
    }

    /**
     * Тип опреции (начисление, снятие комиссии ...)
     * @return int
     */
    public function getOperationType()
    {
        return $this->operation_type;
    }

    /**
     *     Конструктор
     * @param ICalculator $calculator модель, которая будет просчитывать процент
     */
    public function __construct(IDepositEntity $deposit, ICalculator $calculator)
    {
        $this->calculator = $calculator;
        $this->deposit = $deposit;
    }

    /**
     *     Текущий экземпляр депозита
     * @return IDepositEntity
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     *     Выполняет рассчет процента, после чеговызывает соответсвующее событие
     */
    public function run()
    {
        $this->calculated_value = $this->calculator->getCalculated($this->deposit);
        $this->trigger(self::EVENT_AFTER_CALCUlATED);
    }

}
