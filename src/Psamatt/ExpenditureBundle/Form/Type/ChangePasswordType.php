<?php

namespace Psamatt\ExpenditureBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Password must match repeated password',
                'options' => array(
                    'attr' => array('class' => 'password-field')
                ),
                'required' => true,
                'first_options'  => array('label' => 'New Password'),
                'second_options' => array('label' => 'Repeat Password'),
                'error_bubbling' => true,
            ))
            ->add('Update Password', 'submit');
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'error_mapping' => array(
                'isValidPassword' => 'password',
            ),
        ));
    }

    public function getName()
    {
        return 'userPassword';
    }
}