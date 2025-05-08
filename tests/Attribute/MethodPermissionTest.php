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
}
