<?php
namespace dimm\deposit\Mappers;

use dimm\deposit\Entities\deposit\DepositBuilder;

/**
 *
 */
class MySqlMapper
{
    public static function map($rows)
    {
        $deposits = [];
        foreach ($rows as $data) { 
            $deposit     = new DepositBuilder();
            $deposits[] = $deposit
                ->setID($data['id'])
                ->setClientID($data['user_id'])
                ->setAmount((float) $data['amount'])
                ->setPercent((float) $data['percent'])
                ->setPeriod(new \DateTimeImmutable($data['start_at']), new \DateTimeImmutable($data['end_at']))
                ->setStatus($data['status'])
                ->build();
        }
        return $deposits;
    }
}
