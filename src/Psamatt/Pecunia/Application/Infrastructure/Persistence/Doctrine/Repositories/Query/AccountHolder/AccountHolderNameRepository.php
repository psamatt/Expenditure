<?php

namespace Psamatt\Pecunia\Application\Infrastructure\Persistence\Doctrine\Repositories\Query\AccountHolder;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.Query.AccountHolderName.repository")
 */
class AccountHolderNameRepository implements \Psamatt\Pecunia\Query\Repository\AccountHolder\IAccountHolderNameRepository
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
        $sth = $this->connection->prepare('SELECT salutation, firstname, surname from ' . $this->tableName . ' WHERE id = :accountHolderId');

        $sth->execute(['accountHolderId' => $accountHolderId]);

        $row = $sth->fetch(\PDO::FETCH_ASSOC);

        $name = '';
        $name .= !empty($row['salutation'])? $row['salutation'] . ' ': '';
        $name .= !empty($row['firstname'])? $row['firstname'] . ' ': '';
        $name .= !empty($row['surname'])? $row['surname']: '';

        return trim($name);
    }
}