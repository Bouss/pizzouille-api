<?php

namespace App\Content\Domain\ValueObject;

use App\Shared\Domain\Model\AbstractId;

/**
 * @method static IngredientId fromString(string $value)
 * @method static IngredientId random()
 */
readonly class IngredientId extends AbstractId
{
}
