<?php

namespace Gregumo\StarsRentalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Type
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Type
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer")
     */
    private $priority;

    /**
     * @ORM\OneToMany(targetEntity="Gregumo\StarsRentalBundle\Entity\Vehicle", mappedBy="type")
     */
    private $vehicles;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Type
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set priority
     *
     * @param integer $priority
     * @return Type
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->vehicles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add vehicles
     *
     * @param \Gregumo\StarsRentalBundle\Entity\Vehicle $vehicles
     * @return Type
     */
    public function addVehicle(\Gregumo\StarsRentalBundle\Entity\Vehicle $vehicles)
    {
        $this->vehicles[] = $vehicles;

        return $this;
    }

    /**
     * Remove vehicles
     *
     * @param \Gregumo\StarsRentalBundle\Entity\Vehicle $vehicles
     */
    public function removeVehicle(\Gregumo\StarsRentalBundle\Entity\Vehicle $vehicles)
    {
        $this->vehicles->removeElement($vehicles);
    }

    /**
     * Get vehicles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVehicles()
    {
        return $this->vehicles;
    }
}
