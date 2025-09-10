<?php

namespace App\Content\Infrastructure\Specification;

use App\Content\Domain\Model\Ingredient;
use App\Content\Domain\Specification\UniqueIngredientCodeSpecificationInterface;
use App\Shared\Domain\Model\Code;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineUniqueIngredientSpecification implements UniqueIngredientCodeSpecificationInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function isSatisfiedBy(Code $code): bool
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('1')
            ->from(Ingredient::class, 'i')
            ->where('i.code = :code')
            ->setParameter('code', $code)
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        return null === $result;
    }
}