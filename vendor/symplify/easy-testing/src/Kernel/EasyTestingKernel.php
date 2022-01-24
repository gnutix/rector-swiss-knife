<?php

declare (strict_types=1);
namespace EasyCI20220124\Symplify\EasyTesting\Kernel;

use EasyCI20220124\Psr\Container\ContainerInterface;
use EasyCI20220124\Symplify\EasyTesting\ValueObject\EasyTestingConfig;
use EasyCI20220124\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class EasyTestingKernel extends \EasyCI20220124\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : \EasyCI20220124\Psr\Container\ContainerInterface
    {
        $configFiles[] = \EasyCI20220124\Symplify\EasyTesting\ValueObject\EasyTestingConfig::FILE_PATH;
        return $this->create([], [], $configFiles);
    }
}
