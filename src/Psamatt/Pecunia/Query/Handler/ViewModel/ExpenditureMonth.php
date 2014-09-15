<?php

namespace Psamatt\Pecunia\Query\Handler\ViewModel;

use Money\Money;

class ExpenditureMonth
{
    private $id;
    private $calendarMonth;
    private $monthSalary;
    private $totalPaid;
    private $totalOutgoing;
    private $items;

    public function __construct($id, \DateTime $calendarPeriod, Money $monthSalary, Money $totalPaid, Money $totalOutgoing)
    {
        $this->id = $id;
        $this->calendarMonth = $calendarMonth;
        $this->monthSalary = $monthSalary;
        $this->totalPaid = $totalPaid;
        $this->totalOutgoing = $totalOutgoing;
    }

    public function getId() { return $this->id; }
    public function getCalendarMonth() { return $this->calendarMonth; }
    public function getMonthSalary() { return $this->monthSalary; }
    public function getTotalPaid() { return $this->totalPaid; }
    public function getTotalOutgoing() { return $this->totalOutgoing; }
}