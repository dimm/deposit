<?php
namespace dimm\deposit\Entities\Deposit\Base;

use dimm\deposit\Entities\Deposit\Exceptions\DepositEntityException;
use dimm\deposit\Entities\Deposit\Contracts\IPercent;
use dimm\deposit\Entities\Deposit\Traits\ValueTrait;
/**
 * Процент депозита
 */
class Percent implements IPercent
{
	use ValueTrait;
	
	public function __construct($value){
		if (!is_numeric($value))
			throw new DepositEntityException("Percent should be numeric");
		if ($value < 0)
			throw new DepositEntityException("The percentage must be greater than 0 or equal to 0");
		if ($value > 100)
			throw new DepositEntityException("The percentage must be less than 100 or equal to 100");
		$this->value = $value;
	}
}
