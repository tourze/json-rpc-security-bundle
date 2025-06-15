<?php

namespace Tourze\JsonRPCSecurityBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Tourze\JsonRPCSecurityBundle\JsonRPCSecurityBundle;

class IntegrationTestKernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new SecurityBundle();
        // JsonRPCBundle不可用，跳过
        yield new JsonRPCSecurityBundle();
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        // 基本框架配置
        $container->extension('framework', [
            'secret' => 'TEST_SECRET',
            'test' => true,
            'http_method_override' => false,
            'handle_all_throwables' => true,
            'php_errors' => [
                'log' => true,
            ],
        ]);

        // 安全配置
        $container->extension('security', [
            'password_hashers' => [
                'Symfony\Component\Security\Core\User\InMemoryUser' => 'plaintext',
            ],
            'providers' => [
                'users_in_memory' => [
                    'memory' => [
                        'users' => [
                            'user' => [
                                'password' => 'password',
                                'roles' => ['ROLE_USER'],
                            ],
                            'admin' => [
                                'password' => 'password',
                                'roles' => ['ROLE_ADMIN'],
                            ],
                        ],
                    ],
                ],
            ],
            'firewalls' => [
                'main' => [
                    'lazy' => true,
                    'provider' => 'users_in_memory',
                ],
            ],
        ]);
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/JsonRPCSecurityBundle/cache';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/JsonRPCSecurityBundle/logs';
    }
}
