<?php

namespace Tourze\JsonRPCSecurityBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPCSecurityBundle\Service\GrantService;

class GrantServiceTest extends TestCase
{
    public function testConstructor(): void
    {
        $security = $this->createMock(Security::class);
        $grantService = new GrantService($security);
        
        $this->assertInstanceOf(GrantService::class, $grantService);
    }


    public function testCheckProcedure_withNoAttributes(): void
    {
        $security = $this->createMock(Security::class);
        $grantService = new GrantService($security);

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

        // 不应该调用security的isGranted方法
        $security->expects($this->never())->method('isGranted');

        // 调用测试方法，不应抛出异常
        $grantService->checkProcedure($procedure);
        
        // 如果执行到这里，测试通过
        $this->assertTrue(true);
    }
} 