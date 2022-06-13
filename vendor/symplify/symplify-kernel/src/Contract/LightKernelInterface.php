<?php

declare (strict_types=1);
namespace EasyCI202206\Symplify\SymplifyKernel\Contract;

use EasyCI202206\Psr\Container\ContainerInterface;
/**
 * @api
 */
interface LightKernelInterface
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : ContainerInterface;
    public function getContainer() : ContainerInterface;
}
