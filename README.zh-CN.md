# JSON-RPC Security Bundle

[![PHP Version Require](https://poser.pugx.org/tourze/json-rpc-security-bundle/require/php)]
(https://packagist.org/packages/tourze/json-rpc-security-bundle)
[![License](https://poser.pugx.org/tourze/json-rpc-security-bundle/license)]
(https://packagist.org/packages/tourze/json-rpc-security-bundle)
[![Build Status](https://github.com/tourze/php-monorepo/workflows/CI/badge.svg)]
(https://github.com/tourze/php-monorepo/actions)
[![Coverage Status](https://img.shields.io/badge/coverage-100%25-brightgreen)]
(https://github.com/tourze/php-monorepo)

[English](README.md) | [中文](README.zh-CN.md)

## 目录

- [概述](#概述)
- [系统要求](#系统要求)
- [安装](#安装)
- [快速开始](#快速开始)
- [功能特性](#功能特性)
- [使用方法](#使用方法)
  - [基于角色的授权](#基于角色的授权)
  - [方法级别授权](#方法级别授权)
  - [自定义权限属性](#自定义权限属性)
- [架构设计](#架构设计)
  - [核心组件](#核心组件)
  - [工作原理](#工作原理)
  - [异常处理](#异常处理)
- [配置](#配置)
  - [安全配置](#安全配置)
  - [服务配置](#服务配置)
- [高级用法](#高级用法)
  - [自定义属性](#自定义属性)
  - [多重权限级别](#多重权限级别)
- [测试](#测试)
  - [测试覆盖](#测试覆盖)
- [API 参考](#api-参考)
  - [GrantService](#grantservice)
  - [MethodPermission 属性](#methodpermission-属性)
- [常见问题](#常见问题)
- [贡献代码](#贡献代码)
- [许可证](#许可证)

## 概述

为 JSON-RPC 服务提供授权处理机制的 Symfony Bundle。

## 系统要求

- PHP 8.1+
- Symfony 6.4+
- tourze/json-rpc-core
- tourze/bundle-dependency

## 安装

```bash
composer require tourze/json-rpc-security-bundle
```

## 快速开始

1. 在 Symfony 项目中添加此 Bundle：

```php
// config/bundles.php
return [
    // ...
    Tourze\JsonRPCSecurityBundle\JsonRPCSecurityBundle::class => ['all' => true],
];
```

2. 在 JSON-RPC 方法上使用 `IsGranted` 属性：

```php
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;

#[IsGranted(attribute: 'ROLE_ADMIN')]
class AdminMethod implements JsonRpcMethodInterface
{
    public function __invoke(JsonRpcRequest $request): mixed
    {
        // 只有拥有 ROLE_ADMIN 角色的用户才能访问此方法
        return ['message' => 'Hello Admin!'];
    }
}
```

## 功能特性

- 与 Symfony Security 组件无缝集成
- 为 JSON-RPC 方法提供细粒度权限控制
- 基于属性的权限声明
- 支持类级别和方法级别的授权
- 自动化的事件驱动安全检查

## 使用方法

## 基于角色的授权

```php
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;

#[IsGranted(attribute: 'ROLE_USER')]
class UserProfileMethod implements JsonRpcMethodInterface
{
    public function __invoke(JsonRpcRequest $request): mixed
    {
        // 已认证用户可访问
        return ['profile' => 'user data'];
    }
}
```

## 方法级别授权

```php
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;

class UserManagementMethod implements JsonRpcMethodInterface
{
    #[IsGranted(attribute: 'ROLE_ADMIN')]
    public function deleteUser(int $userId): bool
    {
        // 只有管理员可以删除用户
        return true;
    }
    
    #[IsGranted(attribute: 'ROLE_USER')]
    public function viewProfile(int $userId): array
    {
        // 普通用户可以查看资料
        return ['id' => $userId, 'name' => 'John'];
    }
}
```

## 自定义权限属性

使用 `MethodPermission` 属性进行更细粒度的控制：

```php
use Tourze\JsonRPCSecurityBundle\Attribute\MethodPermission;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;

#[MethodPermission("user.edit", "编辑用户信息")]
class UserEditMethod implements JsonRpcMethodInterface
{
    public function __invoke(JsonRpcRequest $request): mixed
    {
        // 自定义权限检查
        return ['success' => true];
    }
}
```

## 架构设计

## 核心组件

- **`GrantService`**: 核心授权服务，执行权限检查
- **`IsGrantSubscriber`**: 事件订阅器，自动触发安全检查
- **`MethodPermission`**: 自定义属性，用于声明方法权限

## 工作原理

1. 当调用 JSON-RPC 方法时，`IsGrantSubscriber` 拦截请求
2. `GrantService` 使用反射分析方法的属性
3. 对当前用户的权限执行安全检查
4. 根据检查结果授予或拒绝访问

## 异常处理

- **`AccessDeniedException`**: 当没有用户认证时抛出
- **`ApiException`**: 当用户缺少所需权限时抛出（错误代码：-3）

## 配置

## 安全配置

确保正确设置 Symfony 安全配置：

```yaml
# config/packages/security.yaml
security:
    providers:
        # 您的用户提供者
    
    firewalls:
        main:
            # 您的防火墙配置
    
    access_control:
        # 您的访问控制规则
```

## 服务配置

Bundle 会自动注册其服务，无需额外配置。

## 高级用法

## 自定义属性

您可以通过扩展 `MethodPermission` 属性来创建自定义权限属性：

```php
use Tourze\JsonRPCSecurityBundle\Attribute\MethodPermission;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class AdminOnly extends MethodPermission
{
    public function __construct(?string $title = '仅管理员')
    {
        parent::__construct('ROLE_ADMIN', $title);
    }
}
```

## 多重权限级别

为复杂的授权场景应用多重权限检查：

```php
#[IsGranted('ROLE_USER')]
#[MethodPermission('user.advanced.access')]
class AdvancedUserMethod implements JsonRpcMethodInterface
{
    // 需要同时具备 ROLE_USER 角色和自定义权限
}
```

## 测试

运行测试套件：

```bash
./vendor/bin/phpunit packages/json-rpc-security-bundle/tests
```

## 测试覆盖

- ✅ `MethodPermission` 属性：完整的单元测试
- ✅ `JsonRPCSecurityBundle`：Bundle 注册测试
- ✅ `JsonRPCSecurityExtension`：依赖注入容器测试
- ✅ `GrantService`：核心授权逻辑测试
- ✅ `IsGrantSubscriber`：事件处理测试
- ✅ 集成测试：SecurityBundle 依赖现已通过 BundleDependencyInterface 正确配置

当前测试状态：**23/23 测试通过**，单元测试覆盖率全面。

## API 参考

## GrantService

```php
public function checkProcedure(JsonRpcMethodInterface $procedure): void
```

检查当前用户是否有权访问给定的过程。

**抛出异常：**
- `AccessDeniedException`：当没有用户认证时
- `ApiException`：当用户缺少所需权限时

## MethodPermission 属性

```php
#[MethodPermission(string $permission, ?string $title = null)]
```

**参数：**
- `$permission`：权限标识符（例如："user.edit"、"admin::users"）
- `$title`：可选的人类可读描述

## 常见问题

### Q: 如何处理复杂的权限逻辑？

A: 您可以在 `GrantService` 中扩展权限检查逻辑，或者实现自定义的权限属性。

### Q: 是否支持动态权限？

A: 是的，`IsGranted` 属性支持表达式语言和动态权限检查。

### Q: 如何调试权限问题？

A: 启用 Symfony 的安全调试功能，查看详细的权限检查日志。

## 贡献代码

1. Fork 仓库
2. 创建功能分支
3. 为新功能添加测试
4. 确保所有测试通过
5. 提交 Pull Request

## 许可证

此项目采用 MIT 许可证。