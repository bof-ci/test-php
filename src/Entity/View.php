<?php

namespace BOF\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="BOF/Repository/ViewsRepository")
 * @ORM\Table(name="views")
 */

class View
{

    use \DefaultEntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="BOF\Entities\ViewEntity", inversedBy="views")
     * @ORM\JoinColumn(name="profiles", referencedColumnName="id")
     */
    protected $profile;

    /**
     * @ORM\Column(type="string")
     * @var DateTime
     */
    protected $date;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $views;

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     * @return View
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return View
     */
    public function setDate(DateTime $date): View
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     * @return View
     */
    public function setViews(int $views): View
    {
        $this->views = $views;
        return $this;
    }


}