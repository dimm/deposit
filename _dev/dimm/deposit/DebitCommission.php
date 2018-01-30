<?php
namespace dimm\deposit;

use dimm\deposit\Base\Component;
/**
 * Компонент комиссии
 */
class DebitCommission extends Component
{
	/**
	 * Тип операции снятия комиссии для лога.
	 * @var int
	 */
	protected $operation_type = self::TYPE_COMMISSION;
}