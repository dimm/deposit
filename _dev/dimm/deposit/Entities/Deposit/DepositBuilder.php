<?php
namespace dimm\deposit\Entities\Deposit;

use dimm\deposit\Entities\Deposit\Base\Amount;
use dimm\deposit\Entities\Deposit\Base\DepositPeriod;
use dimm\deposit\Entities\Deposit\Base\Percent;
use dimm\deposit\Entities\Deposit\Base\Status;

class DepositBuilder
{
    private $id = 1;
    private $client_id;
    private $calculator;
    private $amount;
    private $percent;
    private $period;
    private $status;

    public function __construct()
    {
        $this->client_id = uniqid(); //Приурктить нормальный генератор UUID
        $this->period    = new DepositPeriod(new \DateTimeImmutable(), new \DateTimeImmutable('next year'));
        $this->status    = new Status(Status::STATUS_ACTIVE);
    }

    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setClientID($client_id)
    {
        $this->client_id = $client_id;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = new Amount($amount);
        return $this;
    }
    public function setPercent($percent)
    {
        $this->percent = new Percent($percent);
        return $this;
    }
    public function setPeriod(\DateTimeImmutable $start_at, \DateTimeImmutable $end_at)
    {
        $this->period = new DepositPeriod($start_at, $end_at);
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = new Status($status);
        return $this;
    }

    public function build()
    {
        return new DepositEntity(
            $this->id,
            $this->client_id,
            $this->amount,
            $this->percent,
            $this->period,
            $this->status
        );
    }

}
