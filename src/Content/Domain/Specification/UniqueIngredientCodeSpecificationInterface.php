<?php

namespace App\Content\Domain\Specification;

use App\Shared\Domain\Model\Code;

interface UniqueIngredientCodeSpecificationInterface
{
    public function isSatisfiedBy(Code $code): bool;
}
