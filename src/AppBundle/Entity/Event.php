<?php

namespace AppBundle\Entity;

use AppBundle\Entity\EventComment;
use AppBundle\Entity\Location;
use AppBundle\Entity\User;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event
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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="tripDate", type="datetime", nullable=false)
     */
    private $tripDate;

    /**
     * @var int
     *
     * @ORM\Column(name="nbParticipants", type="integer")
     */
    private $nbParticipants;

    /**
     * @ORM\ManyToMany(targetEntity="User", cascade={"persist"})
     * @ORM\JoinTable(name="community_participants")
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Itinerary")
     * @ORM\JoinColumn(nullable=false)
     */
    private $itinerary;

    /**
     * @ORM\OneToMany(targetEntity="EventComment", mappedBy="event")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\OrderBy({"creationDate" = "DESC"})
     */
    private $comments;


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
     * @return Event
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
     * Set author
     *
     * @param string $author
     *
     * @return Event
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set nbParticipants
     *
     * @param integer $nbParticipants
     *
     * @return Event
     */
    public function setNbParticipants($nbParticipants)
    {
        $this->nbParticipants = $nbParticipants;

        return $this;
    }

    /**
     * Get nbParticipants
     *
     * @return int
     */
    public function getNbParticipants()
    {
        return $this->nbParticipants;
    }

    /**
     * Set participants
     *
     * @param string $participants
     *
     * @return Event
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;

        return $this;
    }

    /**
     * Get participants
     *
     * @return string
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->participants = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add participant
     *
     * @param User $participant
     *
     * @return Event
     */
    public function addParticipant(User $participant)
    {
        $this->participants[] = $participant;

        return $this;
    }

    /**
     * Remove participant
     *
     * @param User $participant
     */
    public function removeParticipant(User $participant)
    {
        $this->participants->removeElement($participant);
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Event
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set tripDate
     *
     * @param \DateTime $tripDate
     *
     * @return Event
     */
    public function setTripDate($tripDate)
    {
        $this->tripDate = $tripDate;

        return $this;
    }

    /**
     * Get tripDate
     *
     * @return \DateTime
     */
    public function getTripDate()
    {
        return $this->tripDate;
    }

    /**
     * Set startPoint
     *
     * @param Location $startPoint
     *
     * @return Event
     */
    public function setStartPoint(Location $startPoint)
    {
        $this->startPoint = $startPoint;

        return $this;
    }

    /**
     * Get startPoint
     *
     * @return Location
     */
    public function getStartPoint()
    {
        return $this->startPoint;
    }

    /**
     * Add comment
     *
     * @param EventComment $comment
     *
     * @return Event
     */
    public function addComment(EventComment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param EventComment $comment
     */
    public function removeComment(EventComment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set itinerary
     *
     * @param \AppBundle\Entity\Itinerary $itinerary
     *
     * @return Event
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
