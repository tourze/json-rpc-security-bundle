<?php

declare(strict_types=1);

namespace Tourze\JsonRPCSecurityBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\JsonRPCSecurityBundle\JsonRPCSecurityBundle;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(JsonRPCSecurityBundle::class)]
#[RunTestsInSeparateProcesses]
final class JsonRPCSecurityBundleTest extends AbstractBundleTestCase
{
}
