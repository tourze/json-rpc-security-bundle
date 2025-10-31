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

        // 创建BeforeMethodApplyEvent的匿名类实现
        $event = new class($method) extends BeforeMethodApplyEvent {
            private JsonRpcMethodInterface $method;

            private bool $getMethodCalled = false;

            public function __construct(JsonRpcMethodInterface $method)
            {
                $this->method = $method;
            }

            public function getMethod(): JsonRpcMethodInterface
            {
                $this->getMethodCalled = true;

                return $this->method;
            }

            public function wasGetMethodCalled(): bool
            {
                return $this->getMethodCalled;
            }
        };

        // 调用被测试的方法，验证它能正常执行而不抛出异常
        $subscriber->beforeMethodApply($event);

        // 验证 getMethod() 被调用了
        $this->assertTrue($event->wasGetMethodCalled(), 'getMethod() should be called');
        $this->assertInstanceOf(IsGrantSubscriber::class, $subscriber);
    }
}
