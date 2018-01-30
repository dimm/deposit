<?php
namespace dimm\deposit;

use dimm\deposit\Base\Component;
/**
 *  Компонент депозит
 */
class Credit extends Component
{
	/**
	 * Тип операции начисление процентов по депозиту для лога.
	 * @var int
	 */
	protected $operation_type = self::TYPE_Deposit;
}