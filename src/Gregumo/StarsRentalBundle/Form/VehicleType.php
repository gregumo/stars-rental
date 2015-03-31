<?php

namespace Gregumo\StarsRentalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VehicleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Nom'))
            ->add('color', null, array('label' => 'Couleur'))
            ->add('isAvailable', null, array('label' => 'Disponible', 'required' => false))
            ->add(
                'type',
                'entity',
                array(
                    'class' => 'GregumoStarsRentalBundle:Type',
                    'property' => 'name',
                    'label' => 'Gamme'
                ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gregumo\StarsRentalBundle\Entity\Vehicle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gregumo_starsrentalbundle_vehicle';
    }
}
