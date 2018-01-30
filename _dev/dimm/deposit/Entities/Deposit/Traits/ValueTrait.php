<?php
namespace dimm\deposit\Entities\Deposit\Traits;

trait ValueTrait{
	private $value;

	public function getValue(){
		return $this->value;
	}

	public function __get($attr){
        if ($attr == 'value')
            return $this->value;
    }

}