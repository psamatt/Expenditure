<?php

namespace Psamatt\Pecunia\Application\Infrastructure\Persistence\Doctrine\Repositories\Query\AccountHolder;

use Money\Currency;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.Query.AccountHolder.global.repository")
 */
class AccountHolderGlobalRepository implements \Psamatt\Pecunia\Query\Repository\AccountHolder\IAccountHolderGlobalRepository
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
        $this->tableName = 'account_holders';
    }

    /** {inheritdoc} */
    public function find($accountHolderId)
    {
        $sth = $this->connection->prepare('SELECT currency, paid_day from ' . $this->tableName . ' WHERE id = :accountHolderId');

        $sth->execute(['accountHolderId' => $accountHolderId]);

        return $sth->fetch(\PDO::FETCH_ASSOC);
    }
}