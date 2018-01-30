<?php
namespace dimm\deposit\Connections;

use dimm\deposit\Contracts\IConnection;

/**
 * Поставщик БД mySql. Использован PDO
 */
class MySQLPDOConnection implements IConnection
{

    /**
     * объект PDO
     */
    protected static $connection;

    /**
     * @inheritdoc
     */
    protected static $config = [];

    /**
     * Блокировка создания объекта через new.
     * Нужна для реализации Singelton объекта
     */
    protected function __construct(){}

    /**
     * Подключение к БД
     *  @param array | object $connection_string
     */
    protected static function connect()
    {
        if (is_array(self::$config)) {
            if (!isset(self::$config['dsn']) || !isset(self::$config['username']) || !isset(self::$config['password'])) {
                throw new \Exception('Some PDO connection params (dsn, username, password) is not established');
            }

            $pdo = new \PDO(self::$config['dsn'], self::$config['username'], self::$config['password']);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$connection = $pdo;
        } else {
            throw new \Exception('Connection config sold be defined');
        }
    }

    /**
     * @inheritdoc
     */
    public static function setConfig($config)
    {
        self::$config = $config;
    }

    /**
     * @inheritdoc
     * @return PDO
     */
    public static function getConnection()
    {
        if (!self::$connection) {
            self::connect();
        }

        return self::$connection;
    }
}
