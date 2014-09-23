<?php

namespace Psamatt\Pecunia\Application\UI\Web\Twig\ViewSupporters;

use Psamatt\Pecunia\Query\Repository\AccountHolder\IAccountHolderNameRepository;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("twig.extension")
 */
class AccountHolderTwigExtension extends \Twig_Extension
{
    /**
     * @DI\InjectParams({
     *     "accountHolderNameRepository" = @DI\Inject("Pecunia.Query.AccountHolderName.repository")
     * })
     */
    public function __construct(IAccountHolderNameRepository $accountHolderNameRepository)
    {
        $this->accountHolderNameRepository = $accountHolderNameRepository;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('accountNameFromId', array($this, 'getAccountNameFromId')),
        );
    }

    public function getAccountNameFromId($id)
    {
        return $this->accountHolderNameRepository->find($id);
    }

    public function getName()
    {
        return 'account_holder_extension';
    }
}