<?php

declare (strict_types=1);
namespace EasyCI202207\Symplify\Astral\Contract;

use EasyCI202207\PhpParser\Node;
interface NodeNameResolverInterface
{
    public function match(Node $node) : bool;
    public function resolve(Node $node) : ?string;
}
