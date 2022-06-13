<?php

declare (strict_types=1);
namespace EasyCI202206\Symplify\Astral\NodeVisitor;

use EasyCI202206\PhpParser\Node;
use EasyCI202206\PhpParser\Node\Expr;
use EasyCI202206\PhpParser\Node\Stmt;
use EasyCI202206\PhpParser\Node\Stmt\Expression;
use EasyCI202206\PhpParser\NodeVisitorAbstract;
final class CallableNodeVisitor extends NodeVisitorAbstract
{
    /**
     * @var callable(Node): (int|Node|null)
     */
    private $callable;
    /**
     * @param callable(Node $node): (int|Node|null) $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }
    /**
     * @return int|\PhpParser\Node|null
     */
    public function enterNode(Node $node)
    {
        $originalNode = $node;
        $callable = $this->callable;
        /** @var int|Node|null $newNode */
        $newNode = $callable($node);
        if ($originalNode instanceof Stmt && $newNode instanceof Expr) {
            return new Expression($newNode);
        }
        return $newNode;
    }
}
