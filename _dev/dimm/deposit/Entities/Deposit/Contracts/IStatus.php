<?php
namespace dimm\deposit\Entities\Deposit\Contracts;

/**
 * Интерфейс объекта начала и окончания депозита
 */
interface IStatus
{
    public function getValue();
}
