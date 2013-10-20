<?php

namespace Psamatt\ExpenditureBundle\Twig\Extension;

use Carbon\Carbon as CarbonDateTime;

class CarbonTwigExtension extends \Twig_Extension {

    public function getFilters()
    {
        return array(
             'carbonize' => new \Twig_Filter_Method($this, 'carbonize'),
        );
    }

    public function carbonize($dateString) {
        return new CarbonDateTime($dateString);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'carbon';
    }
}
