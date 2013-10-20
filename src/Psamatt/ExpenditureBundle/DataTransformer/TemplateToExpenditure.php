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
            $expenditure->setTitle($template->getTitle());
            $expenditure->setPrice($template->getPrice());
            $expenditure->setAmountPaid(0);
            
            return $expenditure;
        }
    
    }