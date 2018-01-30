<?php

namespace dimm\deposit;

use dimm\deposit\Handlers\MySQLCapitalizeHandler;
use dimm\deposit\Calculators\CapitalizePerMonth;
use dimm\deposit\Connections\MySQLPDOConnection;
use dimm\deposit\Loggers\MySQLTransactionLogger;
use dimm\deposit\Loggers\ShowLogger;
use dimm\deposit\DataProviders\MySQLPDOFinder;
use dimm\deposit\Mappers\MySQLMapper;
/**
 * Класс подготавлявает конфигурацию начислений по депозитам и запускает процесс
 */
class CronDeposit
{
    public static $date;

    /**
     * Запуск процесса начисления депозита
     */
    public static function execute()
    {
        $date = self::$date == null ? date('Y-m-d') : self::$date;

        try
        {
            MySQLPDOConnection::setConfig([
                'dsn'      => 'mysql:host=localhost;dbname=deposit',
                'username' => 'mysql',
                'password' => 'mysql',
                'charset'  => 'utf8',

            ]);
            
            $depositsFinder = new MySQLPDOFinder(MySQLPDOConnection::getConnection());
            $deposits       = MySQLMapper::map($depositsFinder->find(new \DateTime($date)));

            foreach ($deposits as $key => $deposit) {
                $credit = new Credit($deposit, new CapitalizePerMonth);
                $credit
                    ->attach('afterCalculated', new MySQLCapitalizeHandler(MySQLPDOConnection::getConnection()))
                    ->attach('afterCalculated', new MySQLTransactionLogger(MySQLPDOConnection::getConnection()))
                    ->attach('afterCalculated', new ShowLogger); //Вывод на экран для теста
                /* Можно прикрутить отправку Email, SMS, снятие налога и т.д......
                $credit->attach('afterCalculated', new \dimm\deposit\Handlers\Mail);
                $credit->attach('afterCalculated', new \dimm\deposit\Handlers\SMS);
                 */


                MySQLPDOConnection::getConnection()->beginTransaction();
                $credit->run();
                MySQLPDOConnection::getConnection()->commit();
            }
        }
        catch (\Exception $e){
            //Какая-то логика обработки искобчительных ситуаций
            throw new \Exception($e->getMessage());
            
        }
    }
}
