<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace EasyCI20220123\Symfony\Component\DependencyInjection\Loader;

use EasyCI20220123\Symfony\Component\Config\Builder\ConfigBuilderGenerator;
use EasyCI20220123\Symfony\Component\Config\Builder\ConfigBuilderGeneratorInterface;
use EasyCI20220123\Symfony\Component\Config\Builder\ConfigBuilderInterface;
use EasyCI20220123\Symfony\Component\Config\FileLocatorInterface;
use EasyCI20220123\Symfony\Component\DependencyInjection\Attribute\When;
use EasyCI20220123\Symfony\Component\DependencyInjection\Container;
use EasyCI20220123\Symfony\Component\DependencyInjection\ContainerBuilder;
use EasyCI20220123\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use EasyCI20220123\Symfony\Component\DependencyInjection\Extension\ConfigurationExtensionInterface;
use EasyCI20220123\Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
/**
 * PhpFileLoader loads service definitions from a PHP file.
 *
 * The PHP file is required and the $container variable can be
 * used within the file to change the container.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class PhpFileLoader extends \EasyCI20220123\Symfony\Component\DependencyInjection\Loader\FileLoader
{
    protected $autoRegisterAliasesForSinglyImplementedInterfaces = \false;
    private $generator;
    public function __construct(\EasyCI20220123\Symfony\Component\DependencyInjection\ContainerBuilder $container, \EasyCI20220123\Symfony\Component\Config\FileLocatorInterface $locator, string $env = null, \EasyCI20220123\Symfony\Component\Config\Builder\ConfigBuilderGeneratorInterface $generator = null)
    {
        parent::__construct($container, $locator, $env);
        $this->generator = $generator;
    }
    /**
     * {@inheritdoc}
     * @param mixed $resource
     * @return mixed
     * @param string|null $type
     */
    public function load($resource, $type = null)
    {
        // the container and loader variables are exposed to the included file below
        $container = $this->container;
        $loader = $this;
        $path = $this->locator->locate($resource);
        $this->setCurrentDir(\dirname($path));
        $this->container->fileExists($path);
        // the closure forbids access to the private scope in the included file
        $load = \Closure::bind(function ($path, $env) use($container, $loader, $resource, $type) {
            return include $path;
        }, $this, \EasyCI20220123\Symfony\Component\DependencyInjection\Loader\ProtectedPhpFileLoader::class);
        try {
            $callback = $load($path, $this->env);
            if (\is_object($callback) && \is_callable($callback)) {
                $this->executeCallback($callback, new \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator($this->container, $this, $this->instanceof, $path, $resource, $this->env), $path);
            }
        } finally {
            $this->instanceof = [];
            $this->registerAliasesForSinglyImplementedInterfaces();
        }
        return null;
    }
    /**
     * {@inheritdoc}
     * @param mixed $resource
     */
    public function supports($resource, string $type = null) : bool
    {
        if (!\is_string($resource)) {
            return \false;
        }
        if (null === $type && 'php' === \pathinfo($resource, \PATHINFO_EXTENSION)) {
            return \true;
        }
        return 'php' === $type;
    }
    /**
     * Resolve the parameters to the $callback and execute it.
     */
    private function executeCallback(callable $callback, \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator, string $path)
    {
        if (!$callback instanceof \Closure) {
            $callback = \Closure::fromCallable($callback);
        }
        $arguments = [];
        $configBuilders = [];
        $r = new \ReflectionFunction($callback);
        $attribute = null;
        foreach (\method_exists($r, 'getAttributes') ? $r->getAttributes(\EasyCI20220123\Symfony\Component\DependencyInjection\Attribute\When::class) : [] as $attribute) {
            if ($this->env === $attribute->newInstance()->env) {
                $attribute = null;
                break;
            }
        }
        if (null !== $attribute) {
            return;
        }
        foreach ($r->getParameters() as $parameter) {
            $reflectionType = $parameter->getType();
            if (!$reflectionType instanceof \ReflectionNamedType) {
                throw new \InvalidArgumentException(\sprintf('Could not resolve argument "$%s" for "%s". You must typehint it (for example with "%s" or "%s").', $parameter->getName(), $path, \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator::class, \EasyCI20220123\Symfony\Component\DependencyInjection\ContainerBuilder::class));
            }
            $type = $reflectionType->getName();
            switch ($type) {
                case \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator::class:
                    $arguments[] = $containerConfigurator;
                    break;
                case \EasyCI20220123\Symfony\Component\DependencyInjection\ContainerBuilder::class:
                    $arguments[] = $this->container;
                    break;
                case \EasyCI20220123\Symfony\Component\DependencyInjection\Loader\FileLoader::class:
                case self::class:
                    $arguments[] = $this;
                    break;
                default:
                    try {
                        $configBuilder = $this->configBuilder($type);
                    } catch (\EasyCI20220123\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException|\LogicException $e) {
                        throw new \InvalidArgumentException(\sprintf('Could not resolve argument "%s" for "%s".', $type . ' $' . $parameter->getName(), $path), 0, $e);
                    }
                    $configBuilders[] = $configBuilder;
                    $arguments[] = $configBuilder;
            }
        }
        // Force load ContainerConfigurator to make env(), param() etc available.
        \class_exists(\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator::class);
        $callback(...$arguments);
        /** @var ConfigBuilderInterface $configBuilder */
        foreach ($configBuilders as $configBuilder) {
            $containerConfigurator->extension($configBuilder->getExtensionAlias(), $configBuilder->toArray());
        }
    }
    /**
     * @param string $namespace FQCN string for a class implementing ConfigBuilderInterface
     */
    private function configBuilder(string $namespace) : \EasyCI20220123\Symfony\Component\Config\Builder\ConfigBuilderInterface
    {
        if (!\class_exists(\EasyCI20220123\Symfony\Component\Config\Builder\ConfigBuilderGenerator::class)) {
            throw new \LogicException('You cannot use the config builder as the Config component is not installed. Try running "composer require symfony/config".');
        }
        if (null === $this->generator) {
            throw new \LogicException('You cannot use the ConfigBuilders without providing a class implementing ConfigBuilderGeneratorInterface.');
        }
        // If class exists and implements ConfigBuilderInterface
        if (\class_exists($namespace) && \is_subclass_of($namespace, \EasyCI20220123\Symfony\Component\Config\Builder\ConfigBuilderInterface::class)) {
            return new $namespace();
        }
        // If it does not start with Symfony\Config\ we dont know how to handle this
        if ('Symfony\\Config\\' !== \substr($namespace, 0, 15)) {
            throw new \EasyCI20220123\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException(\sprintf('Could not find or generate class "%s".', $namespace));
        }
        // Try to get the extension alias
        $alias = \EasyCI20220123\Symfony\Component\DependencyInjection\Container::underscore(\substr($namespace, 15, -6));
        if (\false !== \strpos($alias, '\\')) {
            throw new \EasyCI20220123\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException('You can only use "root" ConfigBuilders from "Symfony\\Config\\" namespace. Nested classes like "Symfony\\Config\\Framework\\CacheConfig" cannot be used.');
        }
        if (!$this->container->hasExtension($alias)) {
            $extensions = \array_filter(\array_map(function (\EasyCI20220123\Symfony\Component\DependencyInjection\Extension\ExtensionInterface $ext) {
                return $ext->getAlias();
            }, $this->container->getExtensions()));
            throw new \EasyCI20220123\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException(\sprintf('There is no extension able to load the configuration for "%s". Looked for namespace "%s", found "%s".', $namespace, $alias, $extensions ? \implode('", "', $extensions) : 'none'));
        }
        $extension = $this->container->getExtension($alias);
        if (!$extension instanceof \EasyCI20220123\Symfony\Component\DependencyInjection\Extension\ConfigurationExtensionInterface) {
            throw new \LogicException(\sprintf('You cannot use the config builder for "%s" because the extension does not implement "%s".', $namespace, \EasyCI20220123\Symfony\Component\DependencyInjection\Extension\ConfigurationExtensionInterface::class));
        }
        $configuration = $extension->getConfiguration([], $this->container);
        $loader = $this->generator->build($configuration);
        return $loader();
    }
}
/**
 * @internal
 */
final class ProtectedPhpFileLoader extends \EasyCI20220123\Symfony\Component\DependencyInjection\Loader\PhpFileLoader
{
}