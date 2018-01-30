<?php
namespace dimm\deposit\DataProviders;

use dimm\deposit\Base\DepositEntity;
use dimm\deposit\Base\Component;
use dimm\deposit\Contracts\IFinder;
use dimm\deposit\Helpers\DateHelper;
use dimm\deposit\DataProviders\PDOConnection;
/**
 * Ищет депозиты в БД
 */
class MySQLPDOFinder implements IFinder
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
     * @inheritdoc
     */
    public function find(\DateTime $date)
    {

        $sql    = "SELECT * FROM `deposit` WHERE ";
        $day    = $date->format('d');
        $where  = "DATE_FORMAT(`start_at`, '%d') = :day AND LAST_DAY(`start_at`) != `start_at`";
        /*
        Если теущий день не последний в этом месяце, но он в диапазоне 28, 29,30,31,
        то нужно исключить начисления депозитам созданным в последний день, например 28 феварля.
        Они будут начислены в последний день месяца
         */
        if (DateHelper::isLastDayInMonth($date)) {
            $where = "DAYOFMONTH(`start_at`) >= :day";
        }
        $sql .= $where . " AND `status` = :status";
        
        $query = $this->getConnection()->prepare($sql);
        $query->execute([
            ':status' => Component::STATUS_ACTIVE,
            ':day'    => (int) $day,
        ]);

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @inheritdoc
     */
    public function findAllActive(){
        
        $sql    = "SELECT * FROM `deposit` WHERE `status` = :status";
       
        $query = $this->getConnection()->prepare($sql);
        $query->execute([
            ':status' => Component::STATUS_ACTIVE,
        ]);

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}
