<?php

namespace Tourze\JsonRPCSecurityBundle\Tests\Attribute;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\JsonRPCSecurityBundle\Attribute\MethodPermission;

/**
 * @internal
 */
#[CoversClass(MethodPermission::class)]
final class MethodPermissionTest extends TestCase
{
    public function testConstructWithSimplePermission(): void
    {
        $permission = 'simple_permission';
        $title = 'Simple Title';

        $attribute = new MethodPermission($permission, $title);

        // 验证标签名称常量
        $this->assertEquals('json_rpc_http_server.method_permission', MethodPermission::JSONRPC_PERMISSION_TAG);

        // 检查实例化是否成功
        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstructWithEntityPermission(): void
    {
        $permission = 'Entity::action';
        $title = null;

        $attribute = new MethodPermission($permission, $title);

        // 验证标签名称常量
        $this->assertEquals('json_rpc_http_server.method_permission', MethodPermission::JSONRPC_PERMISSION_TAG);

        // 检查实例化是否成功
        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstructWithComplexEntityPermission(): void
    {
        $permission = 'User::create::admin';
        $title = '创建用户权限';

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstructWithEmptyPermission(): void
    {
        $permission = '';
        $title = 'Empty Permission Test';

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstructWithNullTitle(): void
    {
        $permission = 'test_permission';
        $title = null;

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstructWithEmptyTitle(): void
    {
        $permission = 'test_permission';
        $title = '';

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstructWithSpecialCharactersInPermission(): void
    {
        $permission = 'user:create-admin@system';
        $title = 'Special Characters Test';

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstructWithOnlyDoubleColons(): void
    {
        $permission = '::';
        $title = 'Double Colons Only';

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstructWithMultipleDoubleColons(): void
    {
        $permission = 'Entity::Sub::Action::Extra';
        $title = 'Multiple Double Colons';

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testTagNameConstant(): void
    {
        $this->assertEquals('json_rpc_http_server.method_permission', MethodPermission::JSONRPC_PERMISSION_TAG);
    }
}
