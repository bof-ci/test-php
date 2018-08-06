<?php

trait DefaultEntityTrait
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int $id
     */
    protected $id;

    /**
     * @var DateTime $created
     * @ORM\Column(type="string")
     */
    protected $created;

    /**
     * @var DateTime $updated
     * @ORM\Column(type="string")
     */
    protected $updated;

    /**
     * @var DateTime $deleted
     * @ORM\Column(type="string")
     */
    protected $deleted;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return DefaultEntityTrait
     */
    public function setId(int $id): DefaultEntityTrait
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * @param string $created
     * @return DefaultEntityTrait
     */
    public function setCreated(string $created): DefaultEntityTrait
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdated(): string
    {
        return $this->updated;
    }

    /**
     * @param string $updated
     * @return DefaultEntityTrait
     */
    public function setUpdated(string $updated): DefaultEntityTrait
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeleted(): string
    {
        return $this->deleted;
    }

    /**
     * @param string $deleted
     * @return DefaultEntityTrait
     */
    public function setDeleted(string $deleted): DefaultEntityTrait
    {
        $this->deleted = $deleted;
        return $this;
    }

}