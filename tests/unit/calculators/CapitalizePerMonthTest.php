<?php
namespace tests\unit\calculators;

use dimm\deposit\Calculators\CapitalizePerMonth;
use dimm\deposit\Entities\deposit\DepositBuilder;
use PHPUnit\Framework\TestCase;

class CapitalizePerMonthTest extends TestCase
{
    public function testSuccess()
    {
        $deposit = (new DepositBuilder())
            ->setAmount(100.0)
            ->setPercent(12.0)
            ->build();
        $calculator = new CapitalizePerMonth;

        $this->assertEquals(1.0, $calculator->getCalculated($deposit));
    }

    public function testFail(){
        $deposit = (new DepositBuilder())
            ->setAmount(100.0)
            ->setPercent(12.0)
            ->build();
        $calculator = new CapitalizePerMonth;

        $this->assertNotEquals(1.01, $calculator->getCalculated($deposit));   
    }
}
