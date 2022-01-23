<?php

declare (strict_types=1);
namespace EasyCI20220123\Symplify\Astral\NodeNameResolver;

use EasyCI20220123\PhpParser\Node;
use EasyCI20220123\PhpParser\Node\Stmt\Property;
use EasyCI20220123\Symplify\Astral\Contract\NodeNameResolverInterface;
final class PropertyNodeNameResolver implements \EasyCI20220123\Symplify\Astral\Contract\NodeNameResolverInterface
{
    public function match(\EasyCI20220123\PhpParser\Node $node) : bool
    {
        return $node instanceof \EasyCI20220123\PhpParser\Node\Stmt\Property;
    }
    /**
     * @param Property $node
     */
    public function resolve(\EasyCI20220123\PhpParser\Node $node) : ?string
    {
        $propertyProperty = $node->props[0];
        return (string) $propertyProperty->name;
    }
}