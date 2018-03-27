<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="integer")
     */
    protected $reputation = 0;

    public function getReputation()
    {
        return $this->reputation;
    }
    public function setReputation($reputation)
    {
        $this->reputation = $reputation;
    }

    public function __construct()
    {
        $this->events = new ArrayCollection();
        parent::__construct();
    }
}