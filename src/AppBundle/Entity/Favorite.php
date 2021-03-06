<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favorite
 *
 * @ORM\Table(name="favorite")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FavoriteRepository")
 */
class Favorite
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
     * @var int
     *
     * @ORM\Column(name="map_id", type="integer")
     */
    private $map_id;

    /**
     * @ORM\ManyToOne(targetEntity="Location", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="favorites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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
     * @return Favorite
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
     * Set lat
     *
     * @param float $lat
     *
     * @return Favorite
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lon
     *
     * @param float $lon
     *
     * @return Favorite
     */
    public function setLon($lon)
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get lon
     *
     * @return float
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Favorite
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set owmId
     *
     * @param integer $owmId
     *
     * @return Favorite
     */
    public function setOwmId($owmId)
    {
        $this->owm_id = $owmId;

        return $this;
    }

    /**
     * Get owmId
     *
     * @return integer
     */
    public function getOwmId()
    {
        return $this->owm_id;
    }

    /**
     * Set mapId
     *
     * @param integer $mapId
     *
     * @return Favorite
     */
    public function setMapId($mapId)
    {
        $this->map_id = $mapId;

        return $this;
    }

    /**
     * Get mapId
     *
     * @return integer
     */
    public function getMapId()
    {
        return $this->map_id;
    }

    /**
     * Set location
     *
     * @param \AppBundle\Entity\Location $location
     *
     * @return Favorite
     */
    public function setLocation(\AppBundle\Entity\Location $location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \AppBundle\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }
}
