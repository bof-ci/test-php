<?php
namespace BOF\Entities;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class View
 * @package BOF\Models
 * @ORM\Entity
 * @ORM\Table(name="views")
 */
class ViewEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="BOF\Entities\ProfileEntity", inversedBy="views")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    private $profile;

    /** @ORM\Column(type="date") */
    private $date;

    /** @ORM\Column(type="integer") */
    private $views;
}