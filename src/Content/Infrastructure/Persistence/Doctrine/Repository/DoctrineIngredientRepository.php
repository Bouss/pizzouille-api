<?php

namespace App\Content\Infrastructure\Persistence\Doctrine\Repository;

use App\Content\Domain\Model\Ingredient;
use App\Content\Domain\Repository\IngredientRepositoryInterface;
use App\Shared\Domain\Model\Code;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<Ingredient>
 */
class DoctrineIngredientRepository extends ServiceEntityRepository implements IngredientRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    public function add(Ingredient $ingredient): void
    {
        $this->getEntityManager()->persist($ingredient);
    }

    public function byCode(Code $code): ?Ingredient
    {
        $qb = $this->createQueryBuilder('i')
            ->where('i.code = :code')
            ->setParameter('code', $code);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
