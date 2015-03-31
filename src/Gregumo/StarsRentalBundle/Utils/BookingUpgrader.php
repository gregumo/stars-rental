<?php

namespace Gregumo\StarsRentalBundle\Utils;

use Gregumo\StarsRentalBundle\Entity\Booking;
use Gregumo\StarsRentalBundle\Entity\Customer;
use Gregumo\StarsRentalBundle\Entity\Type;
use Gregumo\StarsRentalBundle\Entity\Vehicle;
use Doctrine\ORM\EntityManager;

class BookingUpgrader
{

    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }


    public function getUpgradableInfos(Vehicle $vehicle, Customer $customer)
    {

        //Get superior type
        $currentPriority = $vehicle->getType()->getPriority();
        $superiorType = $this->em->getRepository('GregumoStarsRentalBundle:Type')->findOneBy(array('priority' => ($currentPriority + 1)));

        //Check if superior type exists
        if (!$superiorType)
            return array(
                'superiorTypeExists' => false,
                'currentTypeName' => $vehicle->getType()->getName()
            );

        return array(
            'superiorTypeExists' => true,
            'upgradable' => $this->isUpgradable($vehicle, $customer, $superiorType),
            'currentTypeName' => $vehicle->getType()->getName(),
            'superiorTypeName' => $superiorType->getName(),
            'customerName' => $customer->getCompleteName()
        );

    }

    private function isUpgradable(Vehicle $vehicle, Customer $customer, Type $superiorType)
    {

        return
            $this->isTypeAlmostOvercrowded($vehicle->getType()) &&
            $this->isTypeNotToBusy($superiorType) &&
            $this->isCustomerFaithful($customer);

    }

    /*
     * Check if vehicle type has less than 15% available vehicles
     */
    private function isTypeAlmostOvercrowded(Type $type)
    {
        return $this->getAvailableRateFromType($type) < 0.15;
    }

    /*
     * Check if superior vehicle type has more than 50% available vehicles
     */
    private function isTypeNotToBusy(Type $type)
    {
        return $this->getAvailableRateFromType($type) > 0.5;
    }

    /*
     * Return an available rate of vehicules for a type
     */
    private function getAvailableRateFromType(Type $type)
    {
        $typeVehicles = $type->getVehicles();
        $totalVehiclesNb = count($typeVehicles->toArray());

        $availableVehiclesNb = 0;
        foreach ($typeVehicles as $typeVehicle) {
            /* @var Vehicle $typeVehicle */
            if ($typeVehicle->getIsAvailable())
                $availableVehiclesNb++;
        }

        return $availableVehiclesNb / $totalVehiclesNb;
    }

    /*
     * Check if customer have more than 2 reservation in the last 30 days
     */
    private function isCustomerFaithful(Customer $customer)
    {

        $bookings = $customer->getBookings();
        $minDate = new \DateTime();
        $minDate->sub(new \DateInterval('P30D'));
        $recentBookings = 0;


        foreach ($bookings as $booking) {
            /* @var Booking $booking */

            if ($booking->getStart() > $minDate)
                $recentBookings++;
        }

        return $recentBookings > 2;
    }

} 