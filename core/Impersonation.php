<?php

class Impersonation
{
    private const SESSION_KEY = 'impersonated_role';

    public static function setImpersonatedRole(?array $role): void
    {
        if ($role === null || empty($role['IdVaiTro'])) {
            unset($_SESSION[self::SESSION_KEY]);
            return;
        }

        $_SESSION[self::SESSION_KEY] = [
            'IdVaiTro' => $role['IdVaiTro'],
            'TenVaiTro' => $role['TenVaiTro'] ?? null,
        ];
    }

    public static function getImpersonatedRole(): ?array
    {
        $role = $_SESSION[self::SESSION_KEY] ?? null;
        if (empty($role['IdVaiTro'])) {
            return null;
        }

        if (!array_key_exists('TenVaiTro', $role)) {
            $role['TenVaiTro'] = null;
        }

        return $role;
    }

    public static function clear(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
    }

    public static function applyToUser(array $user): array
    {
        $actualRole = $user['ActualIdVaiTro'] ?? ($user['IdVaiTro'] ?? null);
        $actualRoleName = $user['ActualTenVaiTro'] ?? ($user['TenVaiTro'] ?? null);

        if ($actualRole) {
            $user['ActualIdVaiTro'] = $actualRole;
        }

        if ($actualRoleName !== null) {
            $user['ActualTenVaiTro'] = $actualRoleName;
        }

        if ($actualRole !== 'VT_ADMIN') {
            $user['IsImpersonating'] = false;
            return $user;
        }

        $impersonated = self::getImpersonatedRole();
        if (!$impersonated) {
            $user['IsImpersonating'] = false;
            return $user;
        }

        $user['OriginalIdVaiTro'] = $actualRole;
        $user['OriginalTenVaiTro'] = $actualRoleName;
        $user['ImpersonatedIdVaiTro'] = $impersonated['IdVaiTro'];
        $user['ImpersonatedTenVaiTro'] = $impersonated['TenVaiTro'] ?? null;
        $user['IdVaiTro'] = $impersonated['IdVaiTro'];

        if (!empty($impersonated['TenVaiTro'])) {
            $user['TenVaiTro'] = $impersonated['TenVaiTro'];
        }

        $user['IsImpersonating'] = true;

        return $user;
    }
}
