<?php

namespace App\Content\Infrastructure\Persistence\Doctrine\Repository;

use App\Content\Domain\Model\Ingredient;
use App\Content\Domain\Repository\IngredientRepositoryInterface;
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
}
