<?php

declare (strict_types=1);
namespace EasyCI20220607\Symplify\Astral\NodeValue;

use EasyCI20220607\PHPStan\Type\ConstantScalarType;
use EasyCI20220607\PHPStan\Type\UnionType;
final class UnionTypeValueResolver
{
    /**
     * @return mixed[]
     */
    public function resolveConstantTypes(UnionType $unionType) : array
    {
        $resolvedValues = [];
        foreach ($unionType->getTypes() as $unionedType) {
            if (!$unionedType instanceof ConstantScalarType) {
                continue;
            }
            $resolvedValues[] = $unionedType->getValue();
        }
        return $resolvedValues;
    }
}
