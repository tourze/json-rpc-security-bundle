<?php

namespace Tourze\JsonRPCSecurityBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Tourze\JsonRPC\Core\Event\BeforeMethodApplyEvent;
use Tourze\JsonRPCSecurityBundle\Service\GrantService;

/**
 * 支持框架默认的IsGrant写法
 *
 * @see https://symfony.com/bundles/SensioFrameworkExtraBundle/current/annotations/security.html
 */
final class IsGrantSubscriber
{
    public function __construct(
        private readonly GrantService $grantService,
    ) {
    }

    #[AsEventListener(priority: 99)]
    public function beforeMethodApply(BeforeMethodApplyEvent $event): void
    {
        $this->grantService->checkProcedure($event->getMethod());
    }
}
