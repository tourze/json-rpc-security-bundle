<?php

namespace Tourze\JsonRPCSecurityBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;
use Tourze\JsonRPC\Core\Exception\AccessDeniedException;
use Tourze\JsonRPC\Core\Exception\ApiException;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;
use Tourze\JsonRPCSecurityBundle\Service\GrantService;

class JsonRPCSecurityIntegrationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return IntegrationTestKernel::class;
    }

    protected function setUp(): void
    {
        $this->markTestSkipped('因为JsonRPCBundle不可用，集成测试无法运行');
        self::bootKernel();
    }

    private function createTokenForUser(string $username, array $roles): void
    {
        $user = new InMemoryUser($username, 'password', $roles);
        $token = new UsernamePasswordToken($user, 'main', $roles);

        $container = static::getContainer();
        $container->get('security.token_storage')->setToken($token);
    }

    private function clearToken(): void
    {
        $container = static::getContainer();
        $container->get('security.token_storage')->setToken(null);
    }

    public function testGrantService_withAuthenticatedUserAndValidPermission_success(): void
    {
        // 创建一个需要ROLE_ADMIN权限的方法
        $adminMethod = new class() implements JsonRpcMethodInterface {
            #[IsGranted('ROLE_ADMIN')]
            public function dummy(): void
            {
            }

            public function __invoke(JsonRpcRequest $request): mixed
            {
                return [];
            }

            public function execute(): array
            {
                return [];
            }
        };

        // 创建一个拥有ROLE_ADMIN角色的用户令牌
        $this->createTokenForUser('admin', ['ROLE_ADMIN']);

        // 获取GrantService
        $grantService = static::getContainer()->get(GrantService::class);

        // 验证权限检查通过（没有抛出异常）
        $grantService->checkProcedure($adminMethod);

        $this->assertTrue(true); // 如果执行到这里，说明测试通过
    }

    public function testGrantService_withAuthenticatedUserButNoPermission_throwsApiException(): void
    {
        // 创建一个需要ROLE_ADMIN权限的方法
        $adminMethod = new class() implements JsonRpcMethodInterface {
            #[IsGranted('ROLE_ADMIN')]
            public function dummy(): void
            {
            }

            public function __invoke(JsonRpcRequest $request): mixed
            {
                return [];
            }

            public function execute(): array
            {
                return [];
            }
        };

        // 创建一个只有ROLE_USER角色的用户令牌
        $this->createTokenForUser('user', ['ROLE_USER']);

        // 获取GrantService
        $grantService = static::getContainer()->get(GrantService::class);

        // 验证抛出ApiException
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('当前用户未获得访问授权');
        $this->expectExceptionCode(-3);

        $grantService->checkProcedure($adminMethod);
    }

    public function testGrantService_withNoUser_throwsAccessDeniedException(): void
    {
        // 创建一个需要ROLE_ADMIN权限的方法
        $adminMethod = new class() implements JsonRpcMethodInterface {
            #[IsGranted('ROLE_ADMIN')]
            public function dummy(): void
            {
            }

            public function __invoke(JsonRpcRequest $request): mixed
            {
                return [];
            }

            public function execute(): array
            {
                return [];
            }
        };

        // 清除令牌
        $this->clearToken();

        // 获取GrantService
        $grantService = static::getContainer()->get(GrantService::class);

        // 验证抛出AccessDeniedException
        $this->expectException(AccessDeniedException::class);

        $grantService->checkProcedure($adminMethod);
    }

    public function testSecurityService_isCorrectlyInjected(): void
    {
        $container = static::getContainer();
        $security = $container->get(Security::class);

        $this->assertInstanceOf(Security::class, $security);
    }

    protected function tearDown(): void
    {
        $this->clearToken();
        parent::tearDown();
    }
}
