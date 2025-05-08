<?php

namespace Tourze\JsonRPCSecurityBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\JsonRPCSecurityBundle\DependencyInjection\JsonRPCSecurityExtension;
use Tourze\JsonRPCSecurityBundle\EventSubscriber\IsGrantSubscriber;
use Tourze\JsonRPCSecurityBundle\Service\GrantService;

class JsonRPCSecurityExtensionTest extends TestCase
{
    public function testLoad_registerServices(): void
    {
        $container = new ContainerBuilder();
        $extension = new JsonRPCSecurityExtension();

        $extension->load([], $container);

        // 验证服务是否被注册
        $this->assertTrue($container->has(IsGrantSubscriber::class));
        $this->assertTrue($container->has(GrantService::class));

        // 验证服务定义的autoconfigure和autowire设置
        $this->assertTrue($container->getDefinition(IsGrantSubscriber::class)->isAutoconfigured());
        $this->assertTrue($container->getDefinition(IsGrantSubscriber::class)->isAutowired());
        $this->assertTrue($container->getDefinition(GrantService::class)->isAutoconfigured());
        $this->assertTrue($container->getDefinition(GrantService::class)->isAutowired());
    }
}
