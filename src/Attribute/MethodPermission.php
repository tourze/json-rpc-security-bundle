<?php

namespace Tourze\JsonRPCSecurityBundle\Attribute;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * 声明这个方法所需要的权限
 */
#[\Attribute(flags: \Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class MethodPermission extends AutoconfigureTag
{
    public const JSONRPC_PERMISSION_TAG = 'json_rpc_http_server.method_permission';

    public function __construct(string $permission, ?string $title = null)
    {
        $entity = null;
        if (str_contains($permission, '::')) {
            [$entity] = explode('::', $permission, 2);
        }

        parent::__construct(self::JSONRPC_PERMISSION_TAG, [
            'permission' => $permission,
            'entity' => $entity,
            'title' => $title,
        ]);
    }
}
