<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PromocodeUsageRepository")
 */
class PromoCodeUsage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var PromoCode
     * @ORM\ManyToOne(targetEntity="PromoCode")
     */
    private $promocode;

    /**
     * Тут само собой должна быть связь на таблицу с пользователями, чтобы можно было однозначно понять
     * кто использовал промокод, но в рамках теста ограничимся просто стрингом
     *
     * @ORM\Column(type="string")
     */
    private $user = "some_user";

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return PromoCode
     */
    public function getPromocode(): PromoCode
    {
        return $this->promocode;
    }

    /**
     * @param PromoCode $promocode
     */
    public function setPromocode(PromoCode $promocode): void
    {
        $this->promocode = $promocode;
    }
}
