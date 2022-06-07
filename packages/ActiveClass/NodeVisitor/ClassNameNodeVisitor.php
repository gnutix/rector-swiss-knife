<?php

declare (strict_types=1);
namespace EasyCI20220607\Symplify\EasyCI\ActiveClass\NodeVisitor;

use EasyCI20220607\Nette\Utils\Strings;
use EasyCI20220607\PhpParser\Comment\Doc;
use EasyCI20220607\PhpParser\Node;
use EasyCI20220607\PhpParser\Node\Stmt\ClassLike;
use EasyCI20220607\PhpParser\NodeTraverser;
use EasyCI20220607\PhpParser\NodeVisitorAbstract;
final class ClassNameNodeVisitor extends NodeVisitorAbstract
{
    /**
     * @var string
     * @see https://regex101.com/r/LXmPYG/1
     */
    private const API_TAG_REGEX = '#@api\\b#';
    /**
     * @var string|null
     */
    private $className = null;
    /**
     * @param Node\Stmt[] $nodes
     * @return Node\Stmt[]
     */
    public function beforeTraverse(array $nodes) : ?array
    {
        $this->className = null;
        return $nodes;
    }
    public function enterNode(Node $node) : ?int
    {
        if (!$node instanceof ClassLike) {
            return null;
        }
        if ($node->name === null) {
            return null;
        }
        if ($this->hasApiTag($node)) {
            return null;
        }
        $this->className = $node->namespacedName->toString();
        return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
    }
    public function getClassName() : ?string
    {
        return $this->className;
    }
    private function hasApiTag(ClassLike $classLike) : bool
    {
        $doc = $classLike->getDocComment();
        if (!$doc instanceof Doc) {
            return \false;
        }
        $matches = Strings::match($doc->getText(), self::API_TAG_REGEX);
        return $matches !== null;
    }
}
