<?php

namespace App\Support\Navigation;

use App\Enums\UserRole;
use App\Models\User;

class MenuBuilder
{
    /**
     * Build the navigation tree for the given user.
     */
    public static function forUser(?User $user): array
    {
        $items = config('navigation.items', []);

        return static::filterItems($items, $user);
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    protected static function filterItems(array $items, ?User $user): array
    {
        $userRole = $user?->role instanceof UserRole ? $user->role : null;

        $filtered = [];

        foreach ($items as $item) {
            if (isset($item['roles'])) {
                $allowedRoles = static::normalizeRoles($item['roles']);

                if ($allowedRoles === []) {
                    continue;
                }

                if (! $userRole || ! in_array($userRole, $allowedRoles, true)) {
                    continue;
                }
            }

            if (isset($item['children']) && is_array($item['children'])) {
                $item['children'] = static::filterItems($item['children'], $user);

                if ($item['children'] === []) {
                    continue;
                }
            }

            $filtered[] = $item;
        }

        return array_values($filtered);
    }

    /**
     * @param  array<int, UserRole|string>  $roles
     * @return array<int, UserRole>
     */
    protected static function normalizeRoles(array $roles): array
    {
        return collect($roles)
            ->map(function ($role) {
                if ($role instanceof UserRole) {
                    return $role;
                }

                if (is_string($role)) {
                    return UserRole::tryFrom($role);
                }

                return null;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}

