<?php
 namespace dimm\deposit\Helpers;
/**
 * Хелпер для работы с датами
 */
class DateHelper{

 	/**
 	 * Проверка даты на то, является ли день последним числом месяца
 	 * @param  \DateTime $date дата для проверки
 	 * @return boolean         
 	 */
 	public static function isLastDayInMonth(\DateTime $date){
 		return $date->format('Y-m-d') == $date->format('Y-m-t');
 	}

 	/**
 	 * Возвращает общее количество дней между датами
 	 * @param  \DateTime $date_from 
 	 * @param  \DateTime $date_to   
 	 * @return int
 	 */
 	public static function getCountDays(\DateTime $date_from, \DateTime $date_to){
		$interval = $date_from->diff($date_to);
 		return $interval->format('%a');
 	}

	/**
 	 * Возвращает общее количество месяцев между датами
 	 * @param  \DateTime $date_from 
 	 * @param  \DateTime $date_to   
 	 * @return int
 	 */
 	public static function getCountMonth(\DateTime $date_from, \DateTime $date_to){
 		$interval = $date_from->diff($date_to);
 		return ($interval->format('%y') * 12) + $interval->format('%m');
 	}




 }