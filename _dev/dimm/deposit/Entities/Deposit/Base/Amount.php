<?php
namespace dimm\deposit\Entities\Deposit\Base;

use dimm\deposit\Entities\Deposit\Exceptions\DepositEntityException;
use dimm\deposit\Entities\Deposit\Contracts\IAmount;
use dimm\deposit\Entities\Deposit\Traits\ValueTrait;
/**
 * Cумма депозита
 */
class Amount implements IAmount
{
	use ValueTrait;
	
	public function __construct($value){
		if (!is_numeric($value))
			throw new DepositEntityException("Amount should be numeric");
		if ($value < 0)
			throw new DepositEntityException("Amount should be bigger than 0");
		$this->value = $value;
	}
}
