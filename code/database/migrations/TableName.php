<?php

declare(strict_types = 1);

namespace Database\Migrations;

final class TableName
{
    public const TABLE_USER = 'users';
    public const TABLE_OAUTH_CLIENTS = 'oauth_clients';
    public const TABLE_PERMISSION = 'permissions';
    public const TABLE_ROLE = 'roles';
    public const TABLE_USER_HAS_ROLES = 'user_has_roles';
    public const TABLE_USER_HAS_PERMISSIONS = 'user_has_permissions';
    public const TABLE_ROLE_HAS_PERMISSIONS = 'role_has_permissions';
    public const TABLE_CLIENT_HAS_PERMISSIONS = 'client_has_permissions';
    public const TABLE_PASSWORD_RESETS = 'password_resets';
    const TABLE_STREAMS = 'streams';
}
