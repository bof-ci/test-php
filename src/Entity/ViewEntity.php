<?php

/**
 * @ORM\Entity
 * @ORM\Table(name="views")
 */
class ViewEntity
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
     * @return ViewEntity
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
     * @return ViewEntity
     */
    public function setDate(DateTime $date): ViewEntity
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
     * @return ViewEntity
     */
    public function setViews(int $views): ViewEntity
    {
        $this->views = $views;
        return $this;
    }


}