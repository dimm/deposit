<?php
namespace dimm\deposit\Contracts;

/**
 * Интерфейс для поиска депозитов.
 * Реализация для разныъ поставщиков будет разной
 */
interface IFinder
{
    /**
     * Возвращает депозиты, по которым должен производиться рассчет на указанную дату
     * @param  \DateTime $date дата
     * @return IDepositEntity
     */
    public function find(\DateTime $date);

    /**
     * Возвращает все активные депозиты
     * @return IDepositEntity
     */
    public function findAllActive();
}
