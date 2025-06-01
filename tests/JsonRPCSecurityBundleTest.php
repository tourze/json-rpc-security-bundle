<?php

namespace Tourze\JsonRPCSecurityBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\JsonRPCSecurityBundle\JsonRPCSecurityBundle;

class JsonRPCSecurityBundleTest extends TestCase
{
    public function testBundleInstantiation(): void
    {
        $bundle = new JsonRPCSecurityBundle();
        $this->assertInstanceOf(JsonRPCSecurityBundle::class, $bundle);
    }

    public function testBundleIsSymfonyBundle(): void
    {
        $bundle = new JsonRPCSecurityBundle();
        $this->assertInstanceOf(Bundle::class, $bundle);
    }

    public function testBundleName(): void
    {
        $bundle = new JsonRPCSecurityBundle();
        // Bundle的名称默认包含Bundle后缀
        $expectedName = 'JsonRPCSecurityBundle';
        $this->assertEquals($expectedName, $bundle->getName());
    }

    public function testBundleNamespace(): void
    {
        $bundle = new JsonRPCSecurityBundle();
        $expectedNamespace = 'Tourze\\JsonRPCSecurityBundle';
        $this->assertEquals($expectedNamespace, $bundle->getNamespace());
    }

    public function testBundlePath(): void
    {
        $bundle = new JsonRPCSecurityBundle();
        $path = $bundle->getPath();
        
        // 验证路径不为空且包含src目录
        $this->assertNotEmpty($path);
        $this->assertStringContainsString('src', $path);
    }
}
