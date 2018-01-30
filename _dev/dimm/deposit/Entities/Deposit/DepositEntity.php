<?php
namespace dimm\deposit\Entities\Deposit;

use dimm\deposit\Entities\Deposit\Contracts\IAmount;
use dimm\deposit\Entities\Deposit\Contracts\IDepositEntity;
use dimm\deposit\Entities\Deposit\Contracts\IPercent;
use dimm\deposit\Entities\Deposit\Contracts\IPeriod;
use dimm\deposit\Entities\Deposit\Contracts\IStatus;

/**
 * Сущность депозита.
 */
class DepositEntity implements IDepositEntity
{

    private $id;
    private $client_id;
    private $amount;
    private $percent;
    private $period;
    private $status;

    public function __construct(
        $id,
        $client_id,
        IAmount $amount,
        IPercent $percent,
        IPeriod $period,
        IStatus $status

    ) {
        $this->id        = $id;
        $this->client_id = $client_id;
        $this->amount    = $amount;
        $this->percent   = $percent;
        $this->period    = $period;
        $this->status    = $status;
    }

    /**
     * ID депозита
     */
    public function getID()
    {
        return $this->id;
    }

    public function getClientID()
    {
        return $this->client_id;
    }

    public function getAmount() // : IAmount
    {
        return $this->amount;
    }

    public function getPercent() //: IPercent
    {
        return $this->percent;
    }

    public function getPeriod() //: IPeriod
    {
        return $this->period;
    }

    public function getStatus() //: IStatus
    {
        return $this->status;
    }

}
