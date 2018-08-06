<?php

namespace BOF\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="BOF/Repository/DailyStatisticsViewsRepository")
 * @ORM\Table(name="daily_statistics_views")
 */
class DailyStatisticView
{

    use \DefaultEntityTrait;

    /**
     * @ORM\ManyToOne(targetEntity="BOF\Entities\ProfileEntity", inversedBy="daily_statistics_views")
     * @ORM\JoinColumn(name="profiles", referencedColumnName="id")
     */
    protected $profile;

    /**
     * @ORM\Column(type="string")
     * @var DateTime
     */
    protected $date;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $user_data;

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     * @return DailyStatisticView
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
     * @return DailyStatisticView
     */
    public function setDate(DateTime $date): DailyStatisticView
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserData(): string
    {
        return $this->user_data;
    }

    /**
     * @param string $user_data
     * @return DailyStatisticView
     */
    public function setUserData(string $user_data): DailyStatisticView
    {
        $this->user_data = $user_data;
        return $this;
    }

}