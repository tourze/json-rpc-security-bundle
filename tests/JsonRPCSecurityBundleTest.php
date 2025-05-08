<?php

namespace Tourze\JsonRPCSecurityBundle\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\JsonRPCSecurityBundle\JsonRPCSecurityBundle;

class JsonRPCSecurityBundleTest extends TestCase
{
    public function testBundleInstantiation(): void
    {
        $bundle = new JsonRPCSecurityBundle();
        $this->assertInstanceOf(JsonRPCSecurityBundle::class, $bundle);
    }
}
