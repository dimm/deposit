<?php
namespace dimm\deposit\Entities\Deposit\Contracts;

/**
 * Интерфейс объекта начала и окончания депозита
 */
interface IPeriod
{

    public function getStartAt();

    public function getEndAt();
}
