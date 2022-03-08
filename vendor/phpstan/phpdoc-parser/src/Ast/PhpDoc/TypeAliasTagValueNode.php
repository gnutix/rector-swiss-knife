<?php

declare (strict_types=1);
namespace EasyCI20220308\PHPStan\PhpDocParser\Ast\PhpDoc;

use EasyCI20220308\PHPStan\PhpDocParser\Ast\NodeAttributes;
use EasyCI20220308\PHPStan\PhpDocParser\Ast\Type\TypeNode;
class TypeAliasTagValueNode implements \EasyCI20220308\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode
{
    use NodeAttributes;
    /** @var string */
    public $alias;
    /** @var TypeNode */
    public $type;
    public function __construct(string $alias, \EasyCI20220308\PHPStan\PhpDocParser\Ast\Type\TypeNode $type)
    {
        $this->alias = $alias;
        $this->type = $type;
    }
    public function __toString() : string
    {
        return \trim("{$this->alias} {$this->type}");
    }
}
