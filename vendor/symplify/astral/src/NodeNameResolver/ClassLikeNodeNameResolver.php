<?php

declare (strict_types=1);
namespace EasyCI20220123\Symplify\Astral\NodeNameResolver;

use EasyCI20220123\PhpParser\Node;
use EasyCI20220123\PhpParser\Node\Stmt\ClassLike;
use EasyCI20220123\Symplify\Astral\Contract\NodeNameResolverInterface;
final class ClassLikeNodeNameResolver implements \EasyCI20220123\Symplify\Astral\Contract\NodeNameResolverInterface
{
    public function match(\EasyCI20220123\PhpParser\Node $node) : bool
    {
        return $node instanceof \EasyCI20220123\PhpParser\Node\Stmt\ClassLike;
    }
    /**
     * @param ClassLike $node
     */
    public function resolve(\EasyCI20220123\PhpParser\Node $node) : ?string
    {
        if (\property_exists($node, 'namespacedName')) {
            return (string) $node->namespacedName;
        }
        if ($node->name === null) {
            return null;
        }
        return (string) $node->name;
    }
}