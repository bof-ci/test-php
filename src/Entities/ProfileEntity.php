<?php
namespace BOF\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Profile
 * @package BOF\Models
 * @ORM\Entity
 * @ORM\Table(name="profiles")
 */
class ProfileEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /** @ORM\Column(type="string") */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="BOF\Entities\ViewEntity", mappedBy="profile")
     */
    private $views;

    /**
     * ProfileEntity constructor.
     */
    public function __construct() {
        $this->views = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getViews()
    {
        return $this->views;
    }
}