<?php

namespace AppBundle\Entity;

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
     * @ORM\Column(name="name", type="string", length=60)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=60)
     */
    private $address;

    /**
     * @var float
     *
     * @ORM\Column(name="lag", type="float")
     */
    private $lag;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float")
     */
    private $lng;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateStart", type="datetime")
     */
    private $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnd", type="datetime", nullable=true)
     */
    private $dateEnd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="postedBy", type="integer")
     */
    private $postedBy;

    /**
     * @var bool
     *
     * @ORM\Column(name="validate", type="boolean")
     */
    private $validate;


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
     * Set address
     *
     * @param string $address
     *
     * @return Event
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set lag
     *
     * @param float $lag
     *
     * @return Event
     */
    public function setLag($lag)
    {
        $this->lag = $lag;

        return $this;
    }

    /**
     * Get lag
     *
     * @return float
     */
    public function getLag()
    {
        return $this->lag;
    }

    /**
     * Set lng
     *
     * @param float $lng
     *
     * @return Event
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     *
     * @return Event
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     *
     * @return Event
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Event
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set postedBy
     *
     * @param integer $postedBy
     *
     * @return Event
     */
    public function setPostedBy($postedBy)
    {
        $this->postedBy = $postedBy;

        return $this;
    }

    /**
     * Get postedBy
     *
     * @return int
     */
    public function getPostedBy()
    {
        return $this->postedBy;
    }

    /**
     * Set validate
     *
     * @param boolean $validate
     *
     * @return Event
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;

        return $this;
    }

    /**
     * Get validate
     *
     * @return bool
     */
    public function getValidate()
    {
        return $this->validate;
    }
}

