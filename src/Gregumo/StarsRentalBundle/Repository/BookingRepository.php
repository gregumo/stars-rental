<?php

namespace Gregumo\StarsRentalBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BookingRepository extends EntityRepository
{

    /**
     * Get all bookings ordered by created date desc
     */
    public function findAll()
    {
        return $this->findBy(array(), array('created' => 'DESC'));
    }

}
