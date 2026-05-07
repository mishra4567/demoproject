<?php

namespace App\Helpers;

class RoleHelper
{
    // ─── Check if has permission ───────────────────
    public static function can(string $module, string $action): bool
    {
        // Super admin — always allowed
        if (session('ADMIN_IS_SUPER') == 1) return true;

        $role = session('ADMIN_ROLE');
        if (!$role) return false;

        $permissions = config('roles.' . $role . '.' . $module, []);
        return in_array($action, $permissions);
    }

    // ─── Check if does NOT have permission ─────────
    public static function cannot(string $module, string $action): bool
    {
        return !self::can($module, $action);
    }

    // ─── Get all permissions for current role ──────
    public static function all(): array
    {
        if (session('ADMIN_IS_SUPER') == 1) return ['*'];

        $role = session('ADMIN_ROLE');
        if (!$role) return [];

        return config('roles.' . $role, []);
    }
}
