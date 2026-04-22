<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SiteClientOnly
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('admin_user');

        if (!$user || !isset($user['nivel']) || $user['nivel'] !== 'client') {
            return redirect()
                ->route('admin.login')
                ->with('notify', [
                    'type' => 'warning',
                    'message' => 'Acesso permitido apenas para usuários do tipo cliente.'
                ]);
        }

        return $next($request);
    }
}