<?php

namespace Psamatt\ExpenditureBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ExpenditurePartialPaidMoneyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('amount', 'number', array(
                'label' => 'How much has been paid?',
                'error_bubbling' => true,
            ))
            ->add('Add Payment', 'submit');
    }

    public function getName()
    {
        return 'expenditurePartialPaidMoney';
    }
}