<?php

namespace Tourze\JsonRPCSecurityBundle\Tests\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Tourze\JsonRPCSecurityBundle\EventSubscriber\IsGrantSubscriber;
use Tourze\JsonRPCSecurityBundle\Service\GrantService;

class IsGrantSubscriberTest extends TestCase
{
    public function testConstructor(): void
    {
        $grantService = $this->createMock(GrantService::class);
        $subscriber = new IsGrantSubscriber($grantService);
        
        $this->assertInstanceOf(IsGrantSubscriber::class, $subscriber);
    }

    public function testBeforeMethodApplyMethodExists(): void
    {
        $grantService = $this->createMock(GrantService::class);
        $subscriber = new IsGrantSubscriber($grantService);
        
        $this->assertTrue(method_exists($subscriber, 'beforeMethodApply'));
    }
} 