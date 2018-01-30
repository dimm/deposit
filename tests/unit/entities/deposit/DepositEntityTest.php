<?php
namespace tests\unit\entities;

use dimm\deposit\Entities\deposit\Base\Amount;
use dimm\deposit\Entities\deposit\Calculators\Capitalize;
use dimm\deposit\Entities\deposit\Base\Percent;
use dimm\deposit\Entities\deposit\Base\Status;
use dimm\deposit\Entities\deposit\DepositEntity;
use dimm\deposit\Entities\deposit\Base\DepositPeriod;
use dimm\deposit\Entities\deposit\DepositBuilder;

use PHPUnit\Framework\TestCase;

class DepositEntityTest extends TestCase
{
    public function testSuccess()
    {
        $deposit = new DepositEntity(
            $entity_id = 1,
            $client_id = 1,
            $amount = new Amount(500.0),
            $percent = new Percent(17.0),
            $period = new DepositPeriod(new \DateTimeImmutable(), new \DateTimeImmutable('next year')),
            $status = new Status(Status::STATUS_ACTIVE)
        );

        $this->assertEquals($client_id, $deposit->getClientID());
        $this->assertEquals($amount, $deposit->getAmount());
        $this->assertEquals($percent, $deposit->getPercent());
        $this->assertEquals($period, $deposit->getPeriod());
        $this->assertEquals($status, $deposit->getStatus());

    }

    public function testWithWrongPeriod()
    {
        $this->expectExceptionMessage('The closing date should be greater than the date of opening of the deposit');
        $deposit = (new DepositBuilder())->setPeriod(new DepositPeriod(new \DateTimeImmutable(), new \DateTimeImmutable()))->build();
    }

    public function testWithWrongAmount()
    {
        $this->expectExceptionMessage('Amount should be bigger than 0');
        $deposit = (new DepositBuilder())->setAmount(new Amount(-5))->build();
    }

    public function testWithWrongPercent()
    {
        $this->expectExceptionMessage('The percentage must be less than 100 or equal to 100');
        $deposit = (new DepositBuilder())->setPercent(new Percent(110))->build();
    }

    public function testWithWrongStatus()
    {
        $this->expectExceptionMessage('Incrorrect status');
        $deposit = (new DepositBuilder())->setStatus('wrong');
    }
}
