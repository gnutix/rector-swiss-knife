<?php

declare (strict_types=1);
namespace EasyCI20220221\PHPStan\PhpDocParser\Ast\PhpDoc;

use EasyCI20220221\PHPStan\PhpDocParser\Ast\NodeAttributes;
use EasyCI20220221\PHPStan\PhpDocParser\Ast\Type\TypeNode;
class MixinTagValueNode implements \EasyCI20220221\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode
{
    use NodeAttributes;
    /** @var TypeNode */
    public $type;
    /** @var string (may be empty) */
    public $description;
    public function __construct(\EasyCI20220221\PHPStan\PhpDocParser\Ast\Type\TypeNode $type, string $description)
    {
        $this->type = $type;
        $this->description = $description;
    }
    public function __toString() : string
    {
        return \trim("{$this->type} {$this->description}");
    }
}