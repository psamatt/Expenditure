<?php

namespace Psamatt\Pecunia\Application\Infrastructure\Persistence\Doctrine\Repositories\Query;

use Psamatt\Pecunia\Query\Handler\ViewModel\ExpenditureMonth;
use Money\Money;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.Query.ExpenditureMonth.Query.repository")
 */
class ExpenditureMonthQueryRepository implements \Psamatt\Pecunia\Query\Repository\IExpenditureMonthRepository
{
    private $connection;
    private $tableName;

    /**
     * @DI\InjectParams({
     *     "connection" = @DI\Inject("doctrine.dbal.read_connection")
     * })
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->tableName = 'month_expenditures';
    }

    /** {inheritdoc} */
    public function findLatestByUser($accountHolderId)
    {
        $sth = $this->connection->prepare('SELECT * from ' . $this->tableName . ' WHERE account_holder_id = :accountHolderId ORDER BY calendar_date DESC');

        $sth->execute(['accountHolderId' => $accountHolderId]);

        return $this->bind($sth->fetch());
    }

    /** {inheritdoc} */
    public function findByMonth($accountHolderId, \DateTime $monthYear)
    {
        $sth = $this->connection->prepare('SELECT * from ' . $this->tableName . '
                WHERE account_holder_id = :accountHolderId
                    AND calendar_month = :calendarMonth');

        $sth->execute([
                'accountHolderId' => $accountHolderId,
                'calendarMonth' => $monthYear->format('Y-m-d'),
            ]);

        return $this->bind($sth->fetch());
    }

    /** { inheritdoc} */
    public function findHistoricOverview($accountHolderId)
    {
        $sth = $this->connection->prepare('SELECT calendar_date, income, currency from ' . $this->tableName . '
                WHERE account_holder_id = :accountHolderId
                ORDER BY calendar_date DESC');

        $sth->execute([
                'accountHolderId' => $accountHolderId,
            ]);

        return $sth->fetchAll();
    }

    private function bind($row)
    {
        if (!$row) {
            return null;
        }

        return new ExpenditureMonth(
                $row['id'],
                new \DateTime($row['calendar_date']),
                Money::{$row['currency']}(floatval($row['income'])),
                Money::{$row['currency']}(floatval($row['total_paid'])),
                Money::{$row['currency']}(floatval($row['total_outgoing']))
            );
    }
}