<?php
// src/Gregumo/StarsRentalBundle/DataFixtures/ORM/LoadTypeData.php

namespace Gregumo\StarsRentalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Gregumo\StarsRentalBundle\Entity\Type;

class LoadTypeData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $inputTypes = array(
            array('name' => 'X-Wing', 'priority' => 0),
            array('name' => 'TieFighter', 'priority' => 1)
        );

        foreach($inputTypes as $inputType) {

            $type = new Type();
            $type->setName($inputType['name']);
            $type->setPriority($inputType['priority']);

            $manager->persist($type);
        }

        $manager->flush();
    }
}