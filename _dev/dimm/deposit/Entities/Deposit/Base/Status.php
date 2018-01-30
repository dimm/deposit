<?php
namespace dimm\deposit\Entities\Deposit\Base;

use dimm\deposit\Entities\Deposit\Contracts\IStatus;
use dimm\deposit\Entities\Deposit\Exceptions\DepositEntityException;
use dimm\deposit\Entities\Deposit\Traits\ValueTrait;

/**
 * Cумма депозита
 */
class Status implements IStatus
{
    use ValueTrait;

    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 0;

    public static $statuses =
        [
        self::STATUS_ACTIVE => 'active',
        self::STATUS_CLOSED => 'closed',
    ];

    public function __construct($status)
    {

        if (!array_key_exists($status, self::$statuses)) {
            throw new DepositEntityException("Incrorrect status");
        }

        $this->value = $status;
    }

}
