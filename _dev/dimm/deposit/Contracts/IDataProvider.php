<?php
namespace dimm\deposit\Contracts;
/**
 * Интерейс для построения поставщика данных по депозитам.
 * Это может быть БД, API и т.д.
 */
interface IDataProvider
{
	/**
	 * Конфигурация поставщика
	 * @param mixed $config 
	 */
    public static function setConfig($config);

    /**
     * instance Подключения
     */
    public static function getConnection();
}
