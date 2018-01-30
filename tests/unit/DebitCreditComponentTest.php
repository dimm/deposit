<?php
namespace tests\unit;

use dimm\deposit\Calculators\CapitalizePerMonth;
use dimm\deposit\Calculators\GridCommission;
use dimm\deposit\Credit;
use dimm\deposit\DebitCommission;
use dimm\deposit\DataProviders\MySQLPDOFinder;
use dimm\deposit\Entities\deposit\DepositBuilder;
use dimm\deposit\Handlers\MySQLCapitalizeHandler;
use dimm\deposit\Handlers\MySQLCommission;
use dimm\deposit\Loggers\MySQLTransactionLogger;
use dimm\deposit\Mappers\MySQLMapper;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;

class DebitCreditComponentTest extends TestCase
{
    use TestCaseTrait;

    public static $pdo;

    public $conn;

    public function getDataSet()
    {
        return $this->createFlatXmlDataSet(__DIR__ . '\..\fixtures\deposit.xml');
    }

    private function truncate()
    {
        $connection = $this->getConnection();
        $pdo = $connection->getConnection();
        
        $tables = $pdo->query("SHOW TABLES FROM `deposit`");
        $tables = $tables->fetchAll(\PDO::FETCH_COLUMN, 0);

        foreach ($tables as $table) {
            $pdo->exec("DROP TABLE IF EXISTS `testDB`.`$table`");
            $pdo->exec("CREATE TABLE `testDB`.`$table` LIKE `deposit`.`$table`");
        }
    }

    private function refreshTransactions(){
        $this->getConnection()->getConnection()->exec("TRUNCATE TABLE `testDB`.`deposit_transaction`");
    }

    final public function getConnection()
    {
        
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new \PDO('mysql:dbname=testDB;host=localhost', 'mysql', 'mysql');
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, 'testDB');
        }

        return $this->conn;
    }

    public function __construct(){
        $this->truncate();
        parent::__construct();
    }

    private function calculate($date){
        $this->refreshTransactions();
        $depositsFinder = new MySQLPDOFinder(self::$pdo);
        $deposits       = MySQLMapper::map($depositsFinder->find(new \DateTime($date)));

        foreach ($deposits as $key => $deposit) {
            $credit = new Credit($deposit, new CapitalizePerMonth);
            $credit
                ->attach('afterCalculated', new MySQLCapitalizeHandler(self::$pdo))
                ->attach('afterCalculated', new MySQLTransactionLogger(self::$pdo));
            $credit->run();
        }
    }

    public function test2017_04_15()
    {
        $this->calculate('2017-04-15');
        
        $this->assertEquals(1, $this->getConnection()->getRowCount('deposit_transaction'));
        
        $query = $this->getConnection()->getConnection()->prepare("SELECT * FROM deposit_transaction LIMIT 1");
        $query->execute();
        $row = $query->fetch(\PDO::FETCH_ASSOC);

        $this->assertEquals(6, $row['deposit_id']);
        $this->assertEquals(1000, $row['amount']);
        $this->assertEquals(1, $row['operation']);

        $query = $this->getConnection()->getConnection()->prepare("SELECT * FROM deposit WHERE id={$row['deposit_id']} LIMIT 1");
        $query->execute();
        $deposit = $query->fetch(\PDO::FETCH_ASSOC);

        $this->assertEquals((100000 + 1000), $deposit['amount']);

    }

    public function testLastDayInMonth()
    {
        $this->calculate('2017-02-28');
        
        $this->assertEquals(4, $this->getConnection()->getRowCount('deposit_transaction'));
        
        $query = $this->getConnection()->getConnection()->prepare("SELECT * FROM deposit_transaction");
        $query->execute();
        $rows = $query->fetchAll(\PDO::FETCH_ASSOC);

        $deposits_id = array_map(function($item){
            return $item['deposit_id'];
        }, $rows);

        $operations = array_map(function($item){
            return $item['operation'];
        }, $rows);

        $amounts = array_map(function($item){
            return $item['amount'];
        }, $rows);
        
        $this->assertEquals([1,2,4,7], $deposits_id);
        $this->assertEquals([10.0, 180.0, 5.0, 180], $amounts);
        $this->assertEquals([1,1,1,1], $operations);
    }

    public function testDebit()
    {
        $this->refreshTransactions();
        $query = $this->getConnection()->getConnection()->prepare("UPDATE deposit SET amount=5000 WHERE id=:id");
        $query->execute([':id' => 1]);


        $depositsFinder = new MySQLPDOFinder(self::$pdo);
        $deposits       = MySQLMapper::map($depositsFinder->findAllActive());

        GridCommission::$grid = [
            [0,     1000,  5, 'min' => 25.0],
            [1000,  10000, 6],
            [10000.0, 'inf', 7, 'max' => 5000.0, 'min' => 1000.0],
        ];

        foreach ($deposits as $key => $deposit) {
            $credit = new DebitCommission($deposit, new GridCommission);
            $credit
                ->attach('afterCalculated', new MySQLCommission(self::$pdo))
                ->attach('afterCalculated', new MySQLTransactionLogger(self::$pdo));
            $credit->run();
        }
        
        $this->assertEquals(7, $this->getConnection()->getRowCount('deposit_transaction'));
        $query = $this->getConnection()->getConnection()->prepare("SELECT * FROM deposit_transaction");
        $query->execute();
        $rows = $query->fetchAll(\PDO::FETCH_ASSOC);

        $deposits_id = array_map(function($item){
            return $item['deposit_id'];
        }, $rows);

        $operation = array_sum(array_map(function($item){
            return $item['operation'];
        }, $rows));

        $this->assertEquals(range(1,7), $deposits_id);
        $this->assertEquals(0, $operation);

        $query = $this->getConnection()->getConnection()->prepare("SELECT * FROM deposit WHERE id=1 LIMIT 1");
        $query->execute();
        $deposit = $query->fetch(\PDO::FETCH_ASSOC);

        $this->assertEquals((5000.0 - 300.0), $deposit['amount']);
    }

}
