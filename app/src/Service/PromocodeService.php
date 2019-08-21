<?php

namespace App\Service;


use App\Classes\PromocodeType\PromoCodeTypeInterface;
use App\Entity\PromoCode;
use App\Exception\PromoException;
use App\Repository\PromocodeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class PromocodeService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PromocodeRepository
     */
    private $promocodeRepository;

    /**
     * PromocodeService constructor.
     * @param EntityManagerInterface $entityManager
     * @param PromocodeRepository $promocodeRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PromocodeRepository $promocodeRepository
    )
    {

        $this->entityManager = $entityManager;
        $this->promocodeRepository = $promocodeRepository;
    }

    /**
     * Если указали дату - то код многоразовый до даты
     * Если указали макс использований - то по количесту использований
     *
     * @param float $discount
     * @param PromoCodeTypeInterface $codeType
     * @param DateTime|null $dueDate
     * @param int $maxUsages
     *
     * @return PromoCode
     * @throws Exception
     */
    public function generatePromoCode(
        float $discount,
        PromoCodeTypeInterface $codeType,
        DateTime $dueDate = null,
        int $maxUsages = 1
    ): PromoCode
    {
        $findCode = function (string $rawCode) {
            return $this->entityManager->getRepository(PromoCode::class)->findOneById($rawCode);
        };

        do {
            $rawCode = $codeType->generate();

            if (strlen($rawCode) > 6) {
                throw new Exception(sprintf(
                    "%s generated code of length %d, but only max 6 currently supported",
                    get_class($codeType),
                    strlen($rawCode)
                ));
            }
        } while ($findCode($rawCode));

        $promoCode = new PromoCode();
        $promoCode->setId($rawCode);
        $promoCode->setDiscount($discount);

        if ($dueDate) {
            $promoCode->setDueDate($dueDate);
            //Такой подход позволит обеспечить какие-то границы, в случае ошибки ввода даты
            $promoCode->setMaxUsages(10000);
        }

        if (!$dueDate) {
            $promoCode->setMaxUsages($maxUsages);
        }

        $this->entityManager->persist($promoCode);
        $this->entityManager->flush();

        return $promoCode;
    }

    /**
     * @param string $rawCode
     *
     * @return PromoCode
     * @throws Exception
     */
    public function getPromoCode(string $rawCode): PromoCode
    {
        $promoCode = $this->entityManager
            ->createQueryBuilder()
            ->select('p')
            ->from(PromoCode::class, 'p')
            ->leftJoin('p.usages', 'usages')
            ->where('p.id = :code')
            ->setParameter(':code', $rawCode)
            ->getQuery()
            // Здесь можно использовать result cache который будет очищаться каждый раз когда промокод используют
            // При условии что операция получения информации по коду - выполняется часто
//            ->useResultCache(true, 3600, sprintf("promo:%s", $rawCode))
            ->getResult();

        /** @var PromoCode $promoCode */
        $promoCode = $promoCode ? $promoCode[0] : null;

        if (!$promoCode) {
            throw new PromoException("Promocode not found");
        }

        if (!$promoCode->isFresh()) {
            throw new PromoException("Promocode expired");
        }

        return $promoCode;
    }
}
