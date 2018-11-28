<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Waypoint
 *
 * @ORM\Table(name="waypoint")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WaypointRepository")
 */
class Waypoint
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Itinerary", inversedBy="waypoints")
     * @ORM\JoinColumn(nullable=false)
     */
    private $itinerary;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Waypoint
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Waypoint
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set itinerary
     *
     * @param \AppBundle\Entity\Itinerary $itinerary
     *
     * @return Waypoint
     */
    public function setItinerary(\AppBundle\Entity\Itinerary $itinerary)
    {
        $this->itinerary = $itinerary;

        return $this;
    }

    /**
     * Get itinerary
     *
     * @return \AppBundle\Entity\Itinerary
     */
    public function getItinerary()
    {
        return $this->itinerary;
    }
}
