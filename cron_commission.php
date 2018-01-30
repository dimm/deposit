<?php  
	
	//Скрипт, который будет вызываться кроном 1 числа каждого месяца
	require(__DIR__.'\vendor\autoload.php');
	//Непосредственно запуск на сняте комиссии.
	dimm\deposit\CronCommission::execute();

?>