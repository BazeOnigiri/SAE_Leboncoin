<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\RoleEnum;

class EnsureNotServiceRole
{
    protected array $serviceRoles = [
        // RoleEnum::SUPER_ADMIN,
        RoleEnum::SERVICE_PETITE_ANNONCE,
        RoleEnum::DIRECTEUR_SERVICE_PETITE_ANNONCE,
        RoleEnum::SERVICE_IMOBILIER,
        RoleEnum::DIRECTEUR_SERVICE_IMOBILIER,
        RoleEnum::SERVICE_INSCRIPTION,
        RoleEnum::DIRECTEUR_SERVICE_INSCRIPTION,
        RoleEnum::SERVICE_LOCATION,
        RoleEnum::DIRECTEUR_SERVICE_LOCATION,
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            foreach ($this->serviceRoles as $role) {
                if ($user->hasRole($role->value)) {
                    abort(403, 'Les comptes de service ne peuvent pas accéder à cette page.');
                }
            }
        }

        return $next($request);
    }
}
