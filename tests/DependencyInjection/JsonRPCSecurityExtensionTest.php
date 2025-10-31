<?php

namespace Tourze\JsonRPCSecurityBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\JsonRPCSecurityBundle\DependencyInjection\JsonRPCSecurityExtension;
use Tourze\JsonRPCSecurityBundle\EventSubscriber\IsGrantSubscriber;
use Tourze\JsonRPCSecurityBundle\Service\GrantService;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(JsonRPCSecurityExtension::class)]
final class JsonRPCSecurityExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    public function testLoadRegisterServices(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');
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

    public function testLoadWithEmptyConfig(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');
        $extension = new JsonRPCSecurityExtension();

        // 使用空配置数组
        $extension->load([], $container);

        // 验证服务仍然被正确注册
        $this->assertTrue($container->has(IsGrantSubscriber::class));
        $this->assertTrue($container->has(GrantService::class));
    }

    public function testLoadWithMultipleConfigs(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');
        $extension = new JsonRPCSecurityExtension();

        // 模拟多个配置块
        $configs = [[], [], []];
        $extension->load($configs, $container);

        // 验证服务仍然被正确注册
        $this->assertTrue($container->has(IsGrantSubscriber::class));
        $this->assertTrue($container->has(GrantService::class));
    }

    public function testLoadServiceDefinitionsExist(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');
        $extension = new JsonRPCSecurityExtension();

        $extension->load([], $container);

        // 验证服务定义存在且配置正确
        $isGrantSubscriberDef = $container->getDefinition(IsGrantSubscriber::class);
        $grantServiceDef = $container->getDefinition(GrantService::class);

        $this->assertEquals(IsGrantSubscriber::class, $isGrantSubscriberDef->getClass());
        $this->assertEquals(GrantService::class, $grantServiceDef->getClass());
    }

    public function testLoadServicesArePrivate(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');
        $extension = new JsonRPCSecurityExtension();

        $extension->load([], $container);

        // 检查服务默认是否为私有的
        $this->assertFalse($container->getDefinition(IsGrantSubscriber::class)->isPublic());
        $this->assertFalse($container->getDefinition(GrantService::class)->isPublic());
    }
}
