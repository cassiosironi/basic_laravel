<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $u = session('admin_user');

        if (!$u || !isset($u['id'])) {
            return redirect()
                ->route('admin.login')
                ->with('notify', [
                    'type' => 'warning',
                    'message' => 'Faça login para acessar o painel.'
                ]);
        }

        return $next($request);
    }
}