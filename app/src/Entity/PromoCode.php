<?php

namespace App\Entity;

use App\Exception\PromoException;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PromocodeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PromoCode implements JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=6)
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $discount = 0.10;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxUsages = 1;

    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dueDate;

    /**
     * @var ArrayCollection|PromoCodeUsage[]
     * @ORM\OneToMany(targetEntity="PromoCodeUsage", mappedBy="promocode")
     */
    private $usages;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     *
     * @throws Exception
     */
    public function validate()
    {
        if (!preg_match_all('@^[a-zA-Z0-9]*$@mi', $this->id)) {
            throw new PromoException("Invalid code, must be [a-zA-Z0-9]");
        }

        if ($this->discount < 0 || $this->discount > 1) {
            throw new PromoException("Invalid discount, provide value > 0 and < 1");
        }

        if ($this->getDueDate() && $this->getDueDate() <= new DateTime()) {
            throw new PromoException("Due date can't be in past");
        }
    }

    /**
     * @return DateTime|null
     */
    public function getDueDate(): ?DateTime
    {
        return $this->dueDate;
    }

    /**
     * @param DateTime|null $dueDate
     */
    public function setDueDate(?DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isFresh()
    {
        if ($this->usages->count() < $this->maxUsages) {
            return true;
        }

        if ($this->getDueDate() && $this->getDueDate() > new DateTime()) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param mixed $discount
     */
    public function setDiscount($discount): void
    {
        $this->discount = $discount;
    }

    /**
     * @return mixed
     */
    public function getMaxUsages()
    {
        return $this->maxUsages;
    }

    /**
     * @param mixed $maxUsages
     */
    public function setMaxUsages($maxUsages): void
    {
        $this->maxUsages = $maxUsages;
    }

    /**
     * @return PromoCodeUsage[]|ArrayCollection
     */
    public function getUsages()
    {
        return $this->usages;
    }

    /**
     * @param PromoCodeUsage[]|ArrayCollection $usages
     */
    public function setUsages($usages): void
    {
        $this->usages = $usages;
    }


    /**
     * Не забываем джойнить на количество использований
     * Иначе будет большое количество запросов
     *
     * @return mixed|void
     */
    public function jsonSerialize(): array
    {
        return [
            'code' => $this->id,
            'discount' => $this->discount,
            'dueDate' => $this->dueDate ? $this->dueDate->format('c') : null,
            'usages' => $this->usages ? $this->usages->count() : 0,
            'maxUsages' => $this->maxUsages
        ];
    }
}
