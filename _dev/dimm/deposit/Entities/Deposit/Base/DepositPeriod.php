<?php
namespace dimm\deposit\Entities\Deposit\Base;

use dimm\deposit\Entities\Deposit\Exceptions\DepositEntityException;
use dimm\deposit\Entities\Deposit\Contracts\IPeriod;
/**
 * Сроки депозита
 */
class DepositPeriod implements IPeriod
{
	protected $start_at;
	protected $end_at;

	public function __construct(\DateTimeImmutable $start_at, \DateTimeImmutable $end_at){
		
		if ($start_at >= $end_at)
			throw new DepositEntityException("The closing date should be greater than the date of opening of the deposit");
		
		$this->start_at = $start_at;
		$this->end_at = $end_at;
	}

	public function getStartAt(){
		return $this->start_at;
	}

    public function getEndAt(){
    	return $this->end_at;
    }
}
