<?php

namespace Tourze\JsonRPCSecurityBundle\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;
use Tourze\JsonRPC\Core\Exception\AccessDeniedException;
use Tourze\JsonRPC\Core\Exception\ApiException;

class GrantService
{
    public function __construct(
        private readonly Security $security,
    ) {
    }

    public function checkProcedure(JsonRpcMethodInterface $procedure): void
    {
        $reflectionClass = new \ReflectionClass($procedure);
        
        // 检查类级别的属性
        foreach ($reflectionClass->getAttributes() as $attribute) {
            /** @var \ReflectionAttribute<object> $attribute */
            if ($attribute->getName() === IsGranted::class || is_subclass_of($attribute->getName(), IsGranted::class)) {
                $item = $attribute->newInstance();
                /* @var IsGranted $item */
                $this->checkIsGranted($item);
            }
        }
        
        // 检查所有方法的属性
        foreach ($reflectionClass->getMethods() as $method) {
            foreach ($method->getAttributes() as $attribute) {
                /** @var \ReflectionAttribute<object> $attribute */
                if ($attribute->getName() === IsGranted::class || is_subclass_of($attribute->getName(), IsGranted::class)) {
                    $item = $attribute->newInstance();
                    /* @var IsGranted $item */
                    $this->checkIsGranted($item);
                }
            }
        }
    }

    private function checkIsGranted(IsGranted $item): void
    {
        if ($this->security->isGranted($item->attribute, $item->subject)) {
            return;
        }

        // 如果用户都没有，说明那就是没登录
        if ($this->security->getUser() === null) {
            throw new AccessDeniedException();
        }
        throw new ApiException('当前用户未获得访问授权', -3);
    }
}
