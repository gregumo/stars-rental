<?php

namespace Gregumo\StarsRentalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class BookingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start')
            ->add('end')
            ->add(
                'customer',
                'entity',
                array(
                    'class' => 'GregumoStarsRentalBundle:Customer',
                    'property' => 'completeName'
                ))
            ->add(
                'vehicle',
                'entity',
                array(
                    'class' => 'GregumoStarsRentalBundle:Vehicle',
                    'property' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('v')
                            ->where('v.isAvailable = TRUE');
                    },
                ))
            ->add(
                'Surclassement ?',
                'button',
                array( 'attr' => array( 'class' => 'isUpgradable' ) )
            )
            ->add('upgrade', null, array('required' => false));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gregumo\StarsRentalBundle\Entity\Booking'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gregumo_starsrentalbundle_booking';
    }
}
