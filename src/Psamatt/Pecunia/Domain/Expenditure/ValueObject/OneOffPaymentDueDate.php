<?php

namespace Psamatt\Pecunia\Domain\Expenditure\ValueObject;

class OneOffPaymentDueDate extends \DateTime
{
    /**
     * Guard
     *
     * @param string $time
     */
    public function __construct($time)
    {
        $this->guardFutureDate($time);

        parent::__construct($time);
    }

    /**
     * Guard
     *
     * @param string $time
     * @throws \InvalidArgumentException
     */
    private function guardFutureDate($time)
    {
        $futureDate = new \DateTime($time);

        if ($futureDate < new \DateTime('first day of next month 00:00:00')) {
            throw new \InvalidArgumentException(sprintf('Time[%s] specified needs to be at least in the next month', $time));
        }
    }
}