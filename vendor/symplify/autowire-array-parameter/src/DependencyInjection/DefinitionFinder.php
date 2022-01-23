<?php

declare (strict_types=1);
namespace EasyCI20220123\Symplify\AutowireArrayParameter\DependencyInjection;

use EasyCI20220123\Symfony\Component\DependencyInjection\ContainerBuilder;
use EasyCI20220123\Symfony\Component\DependencyInjection\Definition;
use EasyCI20220123\Symplify\AutowireArrayParameter\Exception\DependencyInjection\DefinitionForTypeNotFoundException;
use Throwable;
/**
 * @api
 * @see \Symplify\AutowireArrayParameter\Tests\DependencyInjection\DefinitionFinderTest
 */
final class DefinitionFinder
{
    /**
     * @return Definition[]
     */
    public function findAllByType(\EasyCI20220123\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $type) : array
    {
        $definitions = [];
        $containerBuilderDefinitions = $containerBuilder->getDefinitions();
        foreach ($containerBuilderDefinitions as $name => $definition) {
            $class = $definition->getClass() ?: $name;
            if (!$this->doesClassExists($class)) {
                continue;
            }
            if (\is_a($class, $type, \true)) {
                $definitions[$name] = $definition;
            }
        }
        return $definitions;
    }
    public function getByType(\EasyCI20220123\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $type) : \EasyCI20220123\Symfony\Component\DependencyInjection\Definition
    {
        $definition = $this->getByTypeIfExists($containerBuilder, $type);
        if ($definition !== null) {
            return $definition;
        }
        throw new \EasyCI20220123\Symplify\AutowireArrayParameter\Exception\DependencyInjection\DefinitionForTypeNotFoundException(\sprintf('Definition for type "%s" was not found.', $type));
    }
    private function getByTypeIfExists(\EasyCI20220123\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, string $type) : ?\EasyCI20220123\Symfony\Component\DependencyInjection\Definition
    {
        $containerBuilderDefinitions = $containerBuilder->getDefinitions();
        foreach ($containerBuilderDefinitions as $name => $definition) {
            $class = $definition->getClass() ?: $name;
            if (!$this->doesClassExists($class)) {
                continue;
            }
            if (\is_a($class, $type, \true)) {
                return $definition;
            }
        }
        return null;
    }
    private function doesClassExists(string $class) : bool
    {
        try {
            return \class_exists($class);
        } catch (\Throwable $exception) {
            return \false;
        }
    }
}