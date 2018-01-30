<?php  

	//Скрипт, который будет вызываться кроном каждый день
	require(__DIR__.'\vendor\autoload.php');
	
	//Дата для тестирования
	if (isset($argv[1])){
		$date = date('Y-m-d', strtotime($argv[1]));
		print("Used date:".$date."\n");
		dimm\deposit\CronDeposit::$date = $date;
	}

	//Непосредственно запуск на начисление процентов.
	dimm\deposit\CronDeposit::execute();

?>