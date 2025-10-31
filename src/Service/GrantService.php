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
        $this->checkClassAttributes($reflectionClass);

        // 检查所有方法的属性
        $this->checkMethodAttributes($reflectionClass);
    }

    /**
     * @param \ReflectionClass<object> $reflectionClass
     */
    private function checkClassAttributes(\ReflectionClass $reflectionClass): void
    {
        foreach ($reflectionClass->getAttributes() as $attribute) {
            $this->processIsGrantedAttribute($attribute);
        }
    }

    /**
     * @param \ReflectionClass<object> $reflectionClass
     */
    private function checkMethodAttributes(\ReflectionClass $reflectionClass): void
    {
        foreach ($reflectionClass->getMethods() as $method) {
            foreach ($method->getAttributes() as $attribute) {
                $this->processIsGrantedAttribute($attribute);
            }
        }
    }

    /**
     * @param \ReflectionAttribute<object> $attribute
     */
    private function processIsGrantedAttribute(\ReflectionAttribute $attribute): void
    {
        if (IsGranted::class === $attribute->getName() || is_subclass_of($attribute->getName(), IsGranted::class)) {
            $item = $attribute->newInstance();
            if (!$item instanceof IsGranted) {
                return;
            }
            $this->checkIsGranted($item);
        }
    }

    private function checkIsGranted(IsGranted $item): void
    {
        if ($this->security->isGranted($item->attribute, $item->subject)) {
            return;
        }

        // 如果用户都没有，说明那就是没登录
        if (null === $this->security->getUser()) {
            throw new AccessDeniedException();
        }
        throw new ApiException('当前用户未获得访问授权', -3);
    }
}
