<?php

declare (strict_types=1);
namespace EasyCI20220211\Symplify\Astral\NodeValue\NodeValueResolver;

use EasyCI20220211\PhpParser\ConstExprEvaluator;
use EasyCI20220211\PhpParser\Node\Expr;
use EasyCI20220211\PhpParser\Node\Expr\FuncCall;
use EasyCI20220211\PhpParser\Node\Name;
use EasyCI20220211\Symplify\Astral\Contract\NodeValueResolver\NodeValueResolverInterface;
use EasyCI20220211\Symplify\Astral\Exception\ShouldNotHappenException;
use EasyCI20220211\Symplify\Astral\Naming\SimpleNameResolver;
/**
 * @see \Symplify\Astral\Tests\NodeValue\NodeValueResolverTest
 *
 * @implements NodeValueResolverInterface<FuncCall>
 */
final class FuncCallValueResolver implements \EasyCI20220211\Symplify\Astral\Contract\NodeValueResolver\NodeValueResolverInterface
{
    /**
     * @var string[]
     */
    private const EXCLUDED_FUNC_NAMES = ['pg_*'];
    /**
     * @var \Symplify\Astral\Naming\SimpleNameResolver
     */
    private $simpleNameResolver;
    /**
     * @var \PhpParser\ConstExprEvaluator
     */
    private $constExprEvaluator;
    public function __construct(\EasyCI20220211\Symplify\Astral\Naming\SimpleNameResolver $simpleNameResolver, \EasyCI20220211\PhpParser\ConstExprEvaluator $constExprEvaluator)
    {
        $this->simpleNameResolver = $simpleNameResolver;
        $this->constExprEvaluator = $constExprEvaluator;
    }
    public function getType() : string
    {
        return \EasyCI20220211\PhpParser\Node\Expr\FuncCall::class;
    }
    /**
     * @param FuncCall $expr
     * @return mixed
     */
    public function resolve(\EasyCI20220211\PhpParser\Node\Expr $expr, string $currentFilePath)
    {
        if ($this->simpleNameResolver->isName($expr, 'getcwd')) {
            return \dirname($currentFilePath);
        }
        $args = $expr->getArgs();
        $arguments = [];
        foreach ($args as $arg) {
            $arguments[] = $this->constExprEvaluator->evaluateDirectly($arg->value);
        }
        if ($expr->name instanceof \EasyCI20220211\PhpParser\Node\Name) {
            $functionName = (string) $expr->name;
            if (!$this->isAllowedFunctionName($functionName)) {
                return null;
            }
            if (\function_exists($functionName) && \is_callable($functionName)) {
                return \call_user_func_array($functionName, $arguments);
            }
            throw new \EasyCI20220211\Symplify\Astral\Exception\ShouldNotHappenException();
        }
        return null;
    }
    private function isAllowedFunctionName(string $functionName) : bool
    {
        foreach (self::EXCLUDED_FUNC_NAMES as $excludedFuncName) {
            if (\fnmatch($excludedFuncName, $functionName, \FNM_NOESCAPE)) {
                return \false;
            }
        }
        return \true;
    }
}
