<?php

namespace Psamatt\ExpenditureBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SavingAddMoneyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('amount', 'number', array(
                'label' => 'How much?',
                'error_bubbling' => true,
            ))
            ->add('Update Password', 'submit');
    }

    public function getName()
    {
        return 'savingAddMoney';
    }
}