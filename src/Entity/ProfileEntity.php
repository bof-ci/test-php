<?php

namespace BOF\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="BOF/Repository/ProfilesRepository")
 * @ORM\Table(name="profiles")
 */
class ProfileEntity
{

    use \DefaultEntityTrait;

    /**
     * @ORM\Column(type="string")
     * @var string $profile_name
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="BOF\Entity\ViewEntity", mappedBy="profile")
     */
    protected $views;

    /**
     * @ORM\OneToMany(targetEntity="BOF\Entity\DailyStatisticEntity", mappedBy="profile")
     */
    protected $daily_statistics_views;

    /**
     * ProfileEntity constructor.
     */
    public function __construct()
    {
        $this->views = new ArrayCollection();
        $this->daily_statistic_views = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getProfileName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ProfileEntity
     */
    public function setProfileName(string $name): ProfileEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @return mixed
     */
    public function getDailyStatisticsViews()
    {
        return $this->daily_statistics_views;
    }


}