<?php

/**
 * @ORM\Entity(repositoryClass="BOF/Repository/DailyStatisticsViewsRepository")
 * @ORM\Table(name="daily_statistics_views")
 */
class DailyStatisticViewEntity
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
     * @return DailyStatisticViewEntity
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
     * @return DailyStatisticViewEntity
     */
    public function setDate(DateTime $date): DailyStatisticViewEntity
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
     * @return DailyStatisticViewEntity
     */
    public function setUserData(string $user_data): DailyStatisticViewEntity
    {
        $this->user_data = $user_data;
        return $this;
    }

}