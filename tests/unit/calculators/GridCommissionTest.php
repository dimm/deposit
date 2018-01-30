<?php
namespace tests\unit\calculators;

use dimm\deposit\Calculators\GridCommission;
use dimm\deposit\Entities\deposit\DepositBuilder;
use PHPUnit\Framework\TestCase;

class GridCommissionTest extends TestCase
{
    
    protected function getCalculator(){
        GridCommission::$grid = [
            [0,     1000,  5, 'min' => 25.0],
            [1000,  10000, 6],
            [10000.0, 'inf', 7, 'max' => 5000.0, 'min' => 1000.0],
        ];
        return new GridCommission;
    }

    protected function getEntity($amount, $start_at)
    {

        return (new DepositBuilder())
            ->setID(1)
            ->setClientID(1)
            ->setAmount($amount)
            ->setPercent(10.0)
            ->setPeriod($start_at, new \DateTimeImmutable('next year'))
            ->setStatus(1)
            ->build();
    }

    public function testGrid0to1000Min()
    {

        $deposit = $this->getEntity(100.0, new \DateTimeImmutable('-1 year'));
        $calculator = $this->getCalculator();

        $this->assertEquals(25.0, $calculator->getCalculated($deposit));
    }

    public function testGrid0to1000()
    {
        $deposit = $this->getEntity(800.0, new \DateTimeImmutable('-1 year'));
        $calculator = $this->getCalculator();

        $this->assertEquals(40.0, $calculator->getCalculated($deposit));
    }

    public function testGrid1000to10000()
    {
        $deposit = $this->getEntity(1000.0, new \DateTimeImmutable('-1 year'));
        $calculator = $this->getCalculator();

        $this->assertEquals(60.0, $calculator->getCalculated($deposit));
    }


    public function testGrid10000toInfMin()
    {
        $deposit = $this->getEntity(10000.0, new \DateTimeImmutable('-1 year'));
        $calculator = $this->getCalculator();
        $this->assertEquals(1000.0, $calculator->getCalculated($deposit));
    }

    public function testGrid10000toInf()
    {
        $deposit = $this->getEntity(30000.0, new \DateTimeImmutable('-1 year'));
        $calculator = $this->getCalculator();

        $this->assertEquals(2100.0, $calculator->getCalculated($deposit));
    }

    public function testGrid10000toInfMax()
    {
        $deposit = $this->getEntity(500000.0, new \DateTimeImmutable('-1 year'));
        $calculator = $this->getCalculator();

        $this->assertEquals(5000.0, $calculator->getCalculated($deposit));
    }

    public function testGrid1000to10000_only_10_days()
    {   
        $deposit = $this->getEntity(1000.0, new \DateTimeImmutable('-10 days'));

        GridCommission::$grid = [
            [0,  10000,  31, 'min' => 200.0],
        ];
        $calculator = new GridCommission;

        $this->assertEquals(100.0, $calculator->getCalculated($deposit));
    }
}
