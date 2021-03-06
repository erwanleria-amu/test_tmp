<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Itinerary
 *
 * @ORM\Table(name="itinerary")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItineraryRepository")
 */
class Itinerary
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="itineraries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var float
     *
     * @ORM\Column(name="distance", type="float")
     */
    private $distance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="duration", type="time", nullable=true)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="polyline", type="text")
     */
    private $polyline;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Waypoint", mappedBy="itinerary")
     * @ORM\JoinColumn(nullable=false)
     */
    private $waypoints;

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
     * Set name
     *
     * @param string $name
     *
     * @return Itinerary
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
     * Set distance
     *
     * @param float $distance
     *
     * @return Itinerary
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get distance
     *
     * @return float
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set duration
     *
     * @param \DateTime $duration
     *
     * @return Itinerary
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return \DateTime
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set polyline
     *
     * @param string $polyline
     *
     * @return Itinerary
     */
    public function setPolyline($polyline)
    {
        $this->polyline = $polyline;

        return $this;
    }

    /**
     * Get polyline
     *
     * @return string
     */
    public function getPolyline()
    {
        return $this->polyline;
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     *
     * @return Itinerary
     */
    public function setAuthor(\AppBundle\Entity\User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add waypoint
     *
     * @param \AppBundle\Entity\Waypoint $waypoint
     *
     * @return Itinerary
     */
    public function addWaypoint(\AppBundle\Entity\Waypoint $waypoint)
    {
        $this->waypoints[] = $waypoint;

        return $this;
    }

    /**
     * Remove waypoint
     *
     * @param \AppBundle\Entity\Waypoint $waypoint
     */
    public function removeWaypoint(\AppBundle\Entity\Waypoint $waypoint)
    {
        $this->waypoints->removeElement($waypoint);
    }

    /**
     * Get waypoints
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWaypoints()
    {
        return $this->waypoints;
    }
}
