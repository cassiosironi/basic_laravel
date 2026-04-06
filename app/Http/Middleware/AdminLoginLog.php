<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLoginLog
{
    public function handle(Request $request, Closure $next)
    {
        // Só loga quando a sessão foi marcada como "acabou de logar"
        if (session('just_logged_in') == 1) {

            $u = session('admin_user');
            $usuarioId = isset($u['id']) ? (int)$u['id'] : 0;

            if ($usuarioId > 0) {
                $ip = $request->ip();
                $ua = substr((string)$request->userAgent(), 0, 255);

                DB::statement("
                    INSERT INTO admin_login_logs (usuario_id, ip, user_agent, created_at)
                    VALUES (?, ?, ?, NOW())
                ", [$usuarioId, $ip, $ua]);
            }

            // desliga a flag para não logar de novo
            session()->forget('just_logged_in');
        }

        return $next($request);
    }
}