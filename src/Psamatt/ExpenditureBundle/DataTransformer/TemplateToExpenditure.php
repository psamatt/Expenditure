<?php

namespace Psamatt\ExpenditureBundle\DataTransformer;

use Psamatt\ExpenditureBundle\Entity\MonthExpenditure;
use Psamatt\ExpenditureBundle\Entity\MonthExpenditureTemplate;

class TemplateToExpenditure
{
    /**
     * Transform the template to an expenditure item
     *
     * @param MonthExpenditureTemplate $template
     * @return MonthExpenditure
     */
    public function transform(MonthExpenditureTemplate $template)
    {            
        $expenditure = new MonthExpenditure;
        $expenditure->update($template->getTitle(), $template->getPrice());
        
        return $expenditure;
    }
}