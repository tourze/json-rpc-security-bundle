<?php

namespace Tourze\JsonRPCSecurityBundle;

use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;

class JsonRPCSecurityBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            SecurityBundle::class => ['all' => true],
        ];
    }
}
