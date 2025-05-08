# JSON-RPC Security Bundle

这个Symfony Bundle提供了JSON-RPC服务的授权处理机制。

## 安装

```bash
composer require tourze/json-rpc-security-bundle
```

## 功能特性

- 支持Symfony Security组件集成
- 为JSON-RPC方法提供权限检查能力
- 通过属性(Attribute)声明方法所需权限

## 使用方法

1. 在你的Symfony项目中添加此Bundle：

```php
// config/bundles.php
return [
    // ...
    Tourze\JsonRPCSecurityBundle\JsonRPCSecurityBundle::class => ['all' => true],
];
```

2. 在你的JSON-RPC方法类上使用`IsGranted`属性：

```php
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;

#[IsGranted("ROLE_ADMIN")]
class AdminMethod implements JsonRpcMethodInterface
{
    // ...
}
```

3. 也可以使用`MethodPermission`属性来声明更细粒度的权限：

```php
use Tourze\JsonRPCSecurityBundle\Attribute\MethodPermission;
use Tourze\JsonRPC\Core\Domain\JsonRpcMethodInterface;

#[MethodPermission("user.edit", "编辑用户信息")]
class UserEditMethod implements JsonRpcMethodInterface
{
    // ...
}
```

## 单元测试

单元测试使用PHPUnit进行，可以通过以下命令运行：

```bash
./vendor/bin/phpunit packages/json-rpc-security-bundle/tests
```

当前测试覆盖情况：

- `MethodPermission`属性类：基本测试完成
- `JsonRPCSecurityBundle`类：基本测试完成
- `JsonRPCSecurityExtension`类：基本测试完成
- `GrantService`服务：受限于PHP反射API，部分测试被跳过
- `IsGrantSubscriber`事件订阅器：受限于事件类的设计，测试被跳过
- 集成测试：需要`JsonRPCBundle`支持，当前未能完全实现

## 贡献代码

欢迎提交Pull Request或Issue。
