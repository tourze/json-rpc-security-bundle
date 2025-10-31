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

## Table of Contents

- [Overview](#overview)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Features](#features)
- [Usage](#usage)
  - [Basic Role-Based Authorization](#basic-role-based-authorization)
  - [Method-Level Authorization](#method-level-authorization)
  - [Custom Permission Attributes](#custom-permission-attributes)
- [Architecture](#architecture)
  - [Core Components](#core-components)
  - [How It Works](#how-it-works)
  - [Exception Handling](#exception-handling)
- [Configuration](#configuration)
  - [Security Configuration](#security-configuration)
  - [Service Configuration](#service-configuration)
- [Advanced Usage](#advanced-usage)
  - [Custom Attributes](#custom-attributes)
  - [Multiple Permission Levels](#multiple-permission-levels)
- [Testing](#testing)
  - [Test Coverage](#test-coverage)
- [API Reference](#api-reference)
  - [GrantService](#grantservice)
  - [MethodPermission Attribute](#methodpermission-attribute)
- [Contributing](#contributing)
- [License](#license)

## Overview

A Symfony Bundle providing authorization handling for JSON-RPC services.

## Requirements

- PHP 8.1+
- Symfony 6.4+
- tourze/json-rpc-core
- tourze/bundle-dependency

## Installation

```bash
composer require tourze/json-rpc-security-bundle
```

## Quick Start

1. Add the bundle to your Symfony project:

```php
// config/bundles.php
return [
    // ...
    Tourze\JsonRPCSecurityBundle\JsonRPCSecurityBundle::class => ['all' => true],
];
```

2. Use the `IsGranted` attribute on your JSON-RPC methods:

```php
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;

#[IsGranted(attribute: 'ROLE_ADMIN')]
class AdminMethod implements JsonRpcMethodInterface
{
    public function __invoke(JsonRpcRequest $request): mixed
    {
        // Only users with ROLE_ADMIN can access this method
        return ['message' => 'Hello Admin!'];
    }
}
```

## Features

- Seamless integration with Symfony Security component
- Fine-grained permission control for JSON-RPC methods
- Attribute-based permission declarations
- Support for both class-level and method-level authorization
- Automatic event-driven security checks

## Usage

## Basic Role-Based Authorization

```php
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;

#[IsGranted(attribute: 'ROLE_USER')]
class UserProfileMethod implements JsonRpcMethodInterface
{
    public function __invoke(JsonRpcRequest $request): mixed
    {
        // Accessible to authenticated users
        return ['profile' => 'user data'];
    }
}
```

## Method-Level Authorization

```php
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;
use Tourze\JsonRPC\Core\Model\JsonRpcRequest;

class UserManagementMethod implements JsonRpcMethodInterface
{
    #[IsGranted(attribute: 'ROLE_ADMIN')]
    public function deleteUser(int $userId): bool
    {
        // Only admins can delete users
        return true;
    }
    
    #[IsGranted(attribute: 'ROLE_USER')]
    public function viewProfile(int $userId): array
    {
        // Regular users can view profiles
        return ['id' => $userId, 'name' => 'John'];
    }
}
```

## Custom Permission Attributes

For more fine-grained control, use the `MethodPermission` attribute:

```php
use Tourze\JsonRPCSecurityBundle\Attribute\MethodPermission;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;

#[MethodPermission("user.edit", "Edit user information")]
class UserEditMethod implements JsonRpcMethodInterface
{
    public function __invoke(JsonRpcRequest $request): mixed
    {
        // Custom permission check
        return ['success' => true];
    }
}
```

## Architecture

## Core Components

- **`GrantService`**: Core authorization service that checks permissions
- **`IsGrantSubscriber`**: Event subscriber that automatically triggers security checks
- **`MethodPermission`**: Custom attribute for declaring method permissions

## How It Works

1. When a JSON-RPC method is called, the `IsGrantSubscriber` intercepts the request
2. The `GrantService` analyzes the method's attributes using reflection
3. Security checks are performed against the current user's permissions
4. Access is granted or denied based on the results

## Exception Handling

- **`AccessDeniedException`**: Thrown when no user is authenticated
- **`ApiException`**: Thrown when the user lacks required permissions (code: -3)

## Configuration

## Security Configuration

Ensure your Symfony security configuration is properly set up:

```yaml
# config/packages/security.yaml
security:
    providers:
        # Your user providers
    
    firewalls:
        main:
            # Your firewall configuration
    
    access_control:
        # Your access control rules
```

## Service Configuration

The bundle automatically registers its services. No additional configuration is required.

## Advanced Usage

## Custom Attributes

You can create custom permission attributes by extending the `MethodPermission` attribute:

```php
use Tourze\JsonRPCSecurityBundle\Attribute\MethodPermission;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class AdminOnly extends MethodPermission
{
    public function __construct(?string $title = 'Admin Only')
    {
        parent::__construct('ROLE_ADMIN', $title);
    }
}
```

## Multiple Permission Levels

Apply multiple permission checks for complex authorization scenarios:

```php
#[IsGranted('ROLE_USER')]
#[MethodPermission('user.advanced.access')]
class AdvancedUserMethod implements JsonRpcMethodInterface
{
    // Requires both ROLE_USER and custom permission
}
```

## Testing

Run the test suite with:

```bash
./vendor/bin/phpunit packages/json-rpc-security-bundle/tests
```

## Test Coverage

- ✅ `MethodPermission` attribute: Complete unit tests
- ✅ `JsonRPCSecurityBundle`: Bundle registration tests
- ✅ `JsonRPCSecurityExtension`: DI container tests
- ✅ `GrantService`: Core authorization logic tests
- ✅ `IsGrantSubscriber`: Event handling tests
- ✅ Integration tests: SecurityBundle dependency now properly configured via BundleDependencyInterface

Current test status: **23/23 tests passing** with comprehensive unit test coverage.

## API Reference

## GrantService

```php
public function checkProcedure(JsonRpcMethodInterface $procedure): void
```

Checks if the current user has permission to access the given procedure.

**Throws:**
- `AccessDeniedException`: When no user is authenticated
- `ApiException`: When the user lacks required permissions

## MethodPermission Attribute

```php
#[MethodPermission(string $permission, ?string $title = null)]
```

**Parameters:**
- `$permission`: Permission identifier (e.g., "user.edit", "admin::users")
- `$title`: Optional human-readable description

## Contributing

1. Fork the repository
2. Create a feature branch
3. Add tests for new functionality
4. Ensure all tests pass
5. Submit a pull request

## License

This project is licensed under the MIT License.