<?php

namespace Gregumo\StarsRentalBundle\Repository;

use Doctrine\ORM\EntityRepository;

class VehicleRepository extends EntityRepository
{

    /**
     * Get number of available vehicles
     */
    public function getAvailableNb()
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT(v)')
            ->where('v.isAvailable = TRUE')
            ->getQuery()
            ->getSingleScalarResult();
    }

}
