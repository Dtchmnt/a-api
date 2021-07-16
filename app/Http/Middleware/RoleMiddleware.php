<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $role
     * @param null $permission
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $hasRole = false;

        foreach ($roles as $role) {
            if (auth()->user()->hasRole($role)) {
                $hasRole = true;
            };
        }

        if (!$hasRole) {
            return response()->json([
                'errors' => [
                    'message' => [
                        'Доступ запрещен'
                    ]
                ]
            ], 403);
        }

        return $next($request);
    }
}
