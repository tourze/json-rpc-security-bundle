<?php

namespace Tourze\JsonRPCSecurityBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
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

    public function testCheckProcedureWithNoAttributes(): void
    {
        // 使用集成测试环境中的服务
        $grantService = self::getService(GrantService::class);

        // 创建一个没有IsGranted属性的方法
        $procedure = new class implements JsonRpcMethodInterface {
            public function __invoke(JsonRpcRequest $request): mixed
            {
                return [];
            }

            public function execute(): array
            {
                return [];
            }
        };

        // 调用测试方法，不应抛出异常
        $grantService->checkProcedure($procedure);

        // 验证服务实例正确且方法正常执行
        $this->assertInstanceOf(GrantService::class, $grantService);
    }
}
