<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="user")
     */
    private $events;
    /**
     * @ORM\OneToMany(targetEntity="Club", mappedBy="user")
     */
    private $clubs;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->clubs = new ArrayCollection();
        parent::__construct();
    }
    /**
     * @return Collection|events[]
     */
    public function getEvent()
    {
        return $this->events;
    }
    /**
     * @return Collection|clubs[]
     */
    public function getClub()
    {
        return $this->clubs;
    }
}
