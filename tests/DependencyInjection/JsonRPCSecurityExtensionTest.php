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

    public function testLoad_withEmptyConfig(): void
    {
        $container = new ContainerBuilder();
        $extension = new JsonRPCSecurityExtension();

        // 使用空配置数组
        $extension->load([], $container);

        // 验证服务仍然被正确注册
        $this->assertTrue($container->has(IsGrantSubscriber::class));
        $this->assertTrue($container->has(GrantService::class));
    }

    public function testLoad_withMultipleConfigs(): void
    {
        $container = new ContainerBuilder();
        $extension = new JsonRPCSecurityExtension();

        // 模拟多个配置块
        $configs = [[], [], []];
        $extension->load($configs, $container);

        // 验证服务仍然被正确注册
        $this->assertTrue($container->has(IsGrantSubscriber::class));
        $this->assertTrue($container->has(GrantService::class));
    }

    public function testLoad_serviceDefinitionsExist(): void
    {
        $container = new ContainerBuilder();
        $extension = new JsonRPCSecurityExtension();

        $extension->load([], $container);

        // 验证服务定义存在且非空
        $isGrantSubscriberDef = $container->getDefinition(IsGrantSubscriber::class);
        $grantServiceDef = $container->getDefinition(GrantService::class);

        $this->assertNotNull($isGrantSubscriberDef);
        $this->assertNotNull($grantServiceDef);
    }

    public function testLoad_servicesArePrivate(): void
    {
        $container = new ContainerBuilder();
        $extension = new JsonRPCSecurityExtension();

        $extension->load([], $container);

        // 检查服务默认是否为私有的
        $this->assertFalse($container->getDefinition(IsGrantSubscriber::class)->isPublic());
        $this->assertFalse($container->getDefinition(GrantService::class)->isPublic());
    }
}
