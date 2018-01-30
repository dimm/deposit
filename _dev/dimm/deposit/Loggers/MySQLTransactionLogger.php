<?php
namespace dimm\deposit\Loggers;

use dimm\deposit\DataProviders\PDOConnection;
use dimm\deposit\Contracts\IObserver;
use dimm\deposit\Base\Observerable;
/**
 * Сохраняет лог рассчета (начисления, снятия комиссии и т.п.) в БД
 */
class MySQLTransactionLogger extends Observerable
{
    /**
     * Название таблицы БД, которая содержит депозит
     * @var string
     */
    protected $tableName = 'deposit_transaction';

    /**
     * БД
     * @var PDO
     */
    protected $connection;

    public function __construct(\PDO $connection){
        $this->connection = $connection;
    }

    /**
     * @return PDO instance
     */
    protected function getConnection()
    {
        return $this->connection;
    }

    /**
     * @inheritDoc
     */
    public function afterCalculated(IObserver $subject)
    {
        $this->store(
            $subject->getDeposit()->getId(),
            $subject->getCalculatedValue(),
            $subject->getOperationType()
        );
    }

    /**
     * Сохранение данных
     * @param  int $deposit_id
     * @param  double $amount  
     * @todo Возможно вынести сохранение данных в отдельный класс...
     */
    public function store($deposit_id, $amount, $operation_type)
    {
        $set = [];
        /**
         * @todo to do configurable fields
         */
        $data = [
            'deposit_id' => $deposit_id,
            'amount'      => $amount,
            'operation'   => $operation_type,
        ];

        foreach ($data as $attr => $val) {
            $set[] = "`{$attr}`=:$attr";
        }

        $query = $this->getConnection()->prepare("INSERT INTO {$this->tableName} SET " . implode(',', $set));
        return $query->execute($data);
    }
}
