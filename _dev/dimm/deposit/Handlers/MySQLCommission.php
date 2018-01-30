<?php
namespace dimm\deposit\Handlers;

use dimm\deposit\Contracts\IObserverable;
use dimm\deposit\Contracts\IObserver;
use dimm\deposit\DataProviders\PDOConnection;

/**
 * Снятие комиссии с депозита
 */
class MySQLCommission implements IObserverable
{
    
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
            $subject->getDeposit()->getID(),
            $subject->getDeposit()->getAmount()->value - $subject->getCalculatedValue()
        );
    }

   /**
     * Сохранение данных
     * @param  int $deposit_id
     * @param  double $amount  
     * @todo Возможно вынести сохранение данных в отдельный класс...
     */
    public function store($deposit_id, $amount)
    {
        $set = [];
        $data = ['amount' => $amount];

        foreach ($data as $attr => $val) {
            $set[] = "`{$attr}`=:$attr";
        }
        $data['id'] = (int)$deposit_id;

        $sql = "UPDATE `deposit` SET " . implode(',', $set).' WHERE id=:id';
        $query = $this->getConnection()->prepare($sql);
        return $query->execute($data);
    }
}
