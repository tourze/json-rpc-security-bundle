<?php

namespace Tourze\JsonRPCSecurityBundle\Tests\Attribute;

use PHPUnit\Framework\TestCase;
use Tourze\JsonRPCSecurityBundle\Attribute\MethodPermission;

class MethodPermissionTest extends TestCase
{
    public function testConstruct_withSimplePermission(): void
    {
        $permission = 'simple_permission';
        $title = 'Simple Title';

        $attribute = new MethodPermission($permission, $title);

        // 验证标签名称常量
        $this->assertEquals('json_rpc_http_server.method_permission', MethodPermission::JSONRPC_PERMISSION_TAG);

        // 检查实例化是否成功
        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstruct_withEntityPermission(): void
    {
        $permission = 'Entity::action';
        $title = null;

        $attribute = new MethodPermission($permission, $title);

        // 验证标签名称常量
        $this->assertEquals('json_rpc_http_server.method_permission', MethodPermission::JSONRPC_PERMISSION_TAG);

        // 检查实例化是否成功
        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstruct_withComplexEntityPermission(): void
    {
        $permission = 'User::create::admin';
        $title = '创建用户权限';

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstruct_withEmptyPermission(): void
    {
        $permission = '';
        $title = 'Empty Permission Test';

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstruct_withNullTitle(): void
    {
        $permission = 'test_permission';
        $title = null;

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstruct_withEmptyTitle(): void
    {
        $permission = 'test_permission';
        $title = '';

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstruct_withSpecialCharactersInPermission(): void
    {
        $permission = 'user:create-admin@system';
        $title = 'Special Characters Test';

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstruct_withOnlyDoubleColons(): void
    {
        $permission = '::';
        $title = 'Double Colons Only';

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testConstruct_withMultipleDoubleColons(): void
    {
        $permission = 'Entity::Sub::Action::Extra';
        $title = 'Multiple Double Colons';

        $attribute = new MethodPermission($permission, $title);

        $this->assertInstanceOf(MethodPermission::class, $attribute);
    }

    public function testTagNameConstant(): void
    {
        $this->assertEquals('json_rpc_http_server.method_permission', MethodPermission::JSONRPC_PERMISSION_TAG);
        $this->assertIsString(MethodPermission::JSONRPC_PERMISSION_TAG);
        $this->assertNotEmpty(MethodPermission::JSONRPC_PERMISSION_TAG);
    }
}
