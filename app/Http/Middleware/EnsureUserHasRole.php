<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  array<int, string|UserRole>  $roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(Response::HTTP_FORBIDDEN, 'This action is unauthorized.');
        }

        $allowedRoles = collect($roles)->map(function ($role) {
            if ($role instanceof UserRole) {
                return $role;
            }

            $enumRole = UserRole::tryFrom($role);

            if (! $enumRole) {
                throw new InvalidArgumentException("Invalid role [{$role}] provided to the role middleware.");
            }

            return $enumRole;
        });

        if ($allowedRoles->isEmpty()) {
            throw new InvalidArgumentException('No roles provided to the role middleware.');
        }

        if (! $allowedRoles->contains(fn (UserRole $allowedRole) => $user->hasRole($allowedRole))) {
            abort(Response::HTTP_FORBIDDEN, 'This action is unauthorized.');
        }

        return $next($request);
    }
}
