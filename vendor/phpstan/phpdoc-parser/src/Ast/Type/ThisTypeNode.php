<?php

declare (strict_types=1);
namespace EasyCI202207\PHPStan\PhpDocParser\Ast\Type;

use EasyCI202207\PHPStan\PhpDocParser\Ast\NodeAttributes;
class ThisTypeNode implements TypeNode
{
    use NodeAttributes;
    public function __toString() : string
    {
        return '$this';
    }
}
