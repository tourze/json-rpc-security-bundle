<?php

namespace Tourze\JsonRPCSecurityBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;
use Tourze\JsonRPC\Core\Event\BeforeMethodApplyEvent;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPCSecurityBundle\EventSubscriber\IsGrantSubscriber;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;

/**
 * @internal
 */
#[CoversClass(IsGrantSubscriber::class)]
#[RunTestsInSeparateProcesses]
final class IsGrantSubscriberTest extends AbstractEventSubscriberTestCase
{
    protected function onSetUp(): void
    {
        // 初始化测试环境
    }

    public function testConstructor(): void
    {
        $subscriber = self::getService(IsGrantSubscriber::class);
        $this->assertInstanceOf(IsGrantSubscriber::class, $subscriber);
    }

    public function testBeforeMethodApply(): void
    {
        $subscriber = self::getService(IsGrantSubscriber::class);

        // 创建一个实现了JsonRpcMethodInterface的匿名类对象
        $method = new class implements JsonRpcMethodInterface {
            public function __invoke(JsonRpcRequest $request): mixed
            {
                return [];
            }

            public function execute(): array
            {
                return [];
            }
        };

        // 使用 Reflection 验证 beforeMethodApply 方法的行为
        $reflection = new \ReflectionMethod($subscriber, 'beforeMethodApply');
        $this->assertTrue($reflection->isPublic(), 'beforeMethodApply should be public');

        // 验证方法签名
        $parameters = $reflection->getParameters();
        $this->assertCount(1, $parameters, 'beforeMethodApply should have 1 parameter');

        $eventParam = $parameters[0];
        $this->assertSame('event', $eventParam->getName(), 'Parameter should be named "event"');

        $paramType = $eventParam->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $paramType);
        $this->assertSame(BeforeMethodApplyEvent::class, $paramType->getName(), 'Parameter should be BeforeMethodApplyEvent');

        $this->assertInstanceOf(IsGrantSubscriber::class, $subscriber);
    }
}
