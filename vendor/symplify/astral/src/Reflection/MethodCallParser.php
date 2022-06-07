<?php

declare (strict_types=1);
namespace EasyCI20220607\Symplify\Astral\Reflection;

use EasyCI20220607\PhpParser\Node\Expr\MethodCall;
use EasyCI20220607\PhpParser\Node\Stmt\ClassMethod;
use EasyCI20220607\PHPStan\Analyser\Scope;
use EasyCI20220607\PHPStan\Reflection\ClassReflection;
use EasyCI20220607\PHPStan\Type\ObjectType;
use EasyCI20220607\PHPStan\Type\ThisType;
use EasyCI20220607\Symplify\Astral\Naming\SimpleNameResolver;
/**
 * @api
 */
final class MethodCallParser
{
    /**
     * @var \Symplify\Astral\Naming\SimpleNameResolver
     */
    private $simpleNameResolver;
    /**
     * @var \Symplify\Astral\Reflection\ReflectionParser
     */
    private $reflectionParser;
    public function __construct(SimpleNameResolver $simpleNameResolver, ReflectionParser $reflectionParser)
    {
        $this->simpleNameResolver = $simpleNameResolver;
        $this->reflectionParser = $reflectionParser;
    }
    /**
     * @return \PhpParser\Node\Stmt\ClassMethod|null
     */
    public function parseMethodCall(MethodCall $methodCall, Scope $scope)
    {
        $callerType = $scope->getType($methodCall->var);
        if ($callerType instanceof ThisType) {
            $callerType = $callerType->getStaticObjectType();
        }
        if (!$callerType instanceof ObjectType) {
            return null;
        }
        $classReflection = $callerType->getClassReflection();
        if (!$classReflection instanceof ClassReflection) {
            return null;
        }
        $methodName = $this->simpleNameResolver->getName($methodCall->name);
        if ($methodName === null) {
            return null;
        }
        if (!$classReflection->hasNativeMethod($methodName)) {
            return null;
        }
        $methodReflection = $classReflection->getNativeMethod($methodName);
        return $this->reflectionParser->parsePHPStanMethodReflection($methodReflection);
    }
}
