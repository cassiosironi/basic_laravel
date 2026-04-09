<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminLevel
{
    /**
     * Uso:
     * ->middleware('admin.level:admin')
     * ->middleware('admin.level:admin,editor')
     */
    public function handle(Request $request, Closure $next, $levels)
    {
        $user = session('admin_user');

        if (!$user || !isset($user['nivel'])) {
            abort(403);
        }
        $nivel = strtolower(trim($user['nivel']));
        $levels = array_map(fn($l) => strtolower(trim($l)), explode(',', $levels));

        if (!in_array($nivel, $levels)) {
            return redirect()
                ->route('admin.index')
                ->with('notify', [
                    'type' => 'warning',
                    'message' => 'Você não tem permissão para acessar este recurso.'
                ]);

        }

        return $next($request);
    }
}