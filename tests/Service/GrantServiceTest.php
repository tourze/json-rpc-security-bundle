<?php

namespace Tourze\JsonRPCSecurityBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;
use Tourze\JsonRPCSecurityBundle\Service\GrantService;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;

/**
 * @internal
 */
#[CoversClass(GrantService::class)]
#[RunTestsInSeparateProcesses]
final class GrantServiceTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 初始化测试环境
    }

    public function testConstructor(): void
    {
        // 使用集成测试环境中的服务
        $grantService = self::getService(GrantService::class);

        $this->assertInstanceOf(GrantService::class, $grantService);
    }

    public function testCheckProcedureMethodExists(): void
    {
        // 使用集成测试环境中的服务
        $grantService = self::getService(GrantService::class);

        // 使用反射验证 checkProcedure 方法存在且签名正确
        $reflection = new \ReflectionMethod($grantService, 'checkProcedure');

        $this->assertTrue($reflection->isPublic(), 'checkProcedure should be public');

        $parameters = $reflection->getParameters();
        $this->assertCount(1, $parameters, 'checkProcedure should have 1 parameter');

        $param = $parameters[0];
        $this->assertSame('procedure', $param->getName());

        $paramType = $param->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $paramType);
        $this->assertSame(JsonRpcMethodInterface::class, $paramType->getName());

        // 验证返回类型为 void
        $returnType = $reflection->getReturnType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertSame('void', $returnType->getName());
    }
}
