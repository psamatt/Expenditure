<?php

namespace Psamatt\Pecunia\Application\UI\Web\Twig;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Psamatt\Pecunia\Query\Repository\AccountHolder\IAccountHolderGlobalRepository;

use Money\Currency;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("twig.extension")
 */
class ApplicationSupporterTwigExtension extends \Twig_Extension
{
    private $context;
    private $accountHolderGlobalRepository;

    /**
     * @DI\InjectParams({
     *     "context"                            = @DI\Inject("security.context"),
     *     "accountHolderGlobalRepository"      = @DI\Inject("Pecunia.Query.AccountHolder.global.repository")
     * })
     */
    public function __construct(SecurityContextInterface $context, IAccountHolderGlobalRepository $accountHolderGlobalRepository)
    {
        $this->context = $context;
        $this->accountHolderGlobalRepository = $accountHolderGlobalRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        $globals = [];

        if (null !== $this->getUser()) {
            $globals = $this->accountHolderGlobalRepository->find($this->getUser()->getId());

            foreach ($globals as $key => $global) {
                $globals[$amended = preg_replace("/_([a-zA-Z])/e", "strtoupper('$1')", $key)] =  $global;
            };
        }

        return $globals;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('currencyToSymbol', array($this, 'currencyToSymbolFilter')),
        );
    }

    /**
     * Currency to symbol
     *
     */
    public function currencyToSymbolFilter($currency)
    {
        return '£';
    }

    public function getName()
    {
        return 'application_supporter_extension';
    }

    /** @return User */
    private function getUser()
    {
        $token = $this->context->getToken();

        if (null != $token && is_object($token->getUser())) {
            return $token->getUser();
        }

        return null;
    }
}