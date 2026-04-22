<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Notifies;
use App\Support\SanitizesInput;
use App\Models\Usuario;

class AdminAuthController extends Controller
{
    use Notifies, SanitizesInput;

    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string|max:80',
            'senha' => 'required|string|max:120',
        ]);

        try {
            $login = $this->clean($request->input('login'));
            $senha = $this->clean($request->input('senha'));

            $rows = DB::select("
                SELECT id, nome, login, nivel, senha, ativo
                FROM usuarios
                WHERE login = ?
                LIMIT 1
            ", [$login]);

            $user = isset($rows[0]) ? $rows[0] : null;

            if (!$user) {
                 return redirect()
                    ->route('admin.login')
                    ->withInput()
                    ->with('notify', [
                        'type' => 'danger',
                        'message' => 'Usuário não encontrado.'
                    ]);
            }

            if ((int)$user->ativo !== 1) {
                 return redirect()
                    ->route('admin.login')
                    ->withInput()
                    ->with('notify', [
                        'type' => 'warning',
                        'message' => 'Usuário inativo. Contate o administrador.'
                    ]);
            }

            $senhaInformada = $senha;
            $hash = md5($senhaInformada);

            if ($hash !== $user->senha) {
                 return redirect()
                    ->route('admin.login')
                    ->withInput()
                    ->with('notify', [
                        'type' => 'danger',
                        'message' => 'Senha inválida.'
                    ]);
            }

            // Login OK: grava sessão
            $request->session()->regenerate();

            session([
                'admin_user' => [
                    'id'    => (int)$user->id,
                    'nome'  => (string)$user->nome,
                    'login' => (string)$user->login,
                    'nivel' => (string)$user->nivel
                ],
                // flag para o middleware registrar log 1x
                'just_logged_in' => 1
            ]);

           
            // ✅ REGRA: se for CLIENT, redireciona para abertura de chamado
            if ($user->nivel === 'client') {
                return redirect()
                    ->route('site.chamados.create')
                    ->with('notify', [
                        'type' => 'info',
                        'message' => 'Abra seu chamado abaixo.'
                    ]);
            }

            // ✅ Admin / Editor seguem para o admin
            return redirect()
                ->route('admin.index')
                ->with('notify', [
                    'type' => 'success',
                    'message' => 'Login realizado com sucesso.'
                ]);


        } catch (\Throwable $e) {
            return $this->handleException('Erro inesperado no login.');
        }
    }


    public function editPassword()
    {
        return view('admin.perfil.senha');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required|string',
            'nova_senha'  => 'required|string|min:3|confirmed',
        ]);

        $user = session('admin_user');
        $userId = (int) $user['id'];

        // busca senha atual no banco
        $rows = DB::select("
            SELECT senha
            FROM usuarios
            WHERE id = ?
            LIMIT 1
        ", [$userId]);

        if (!isset($rows[0])) {
             return redirect()
                ->route('admin.perfil.senha')
                ->with('notify', [
                    'type' => 'danger',
                    'message' => 'Usuário não encontrado.'
                ]);
        }

        // confere senha atual ***
        if (md5($request->input('senha_atual')) !== $rows[0]->senha) {
             return redirect()
                ->route('admin.perfil.senha')
                ->with('notify', [
                    'type' => 'danger',
                    'message' => 'Senha atual incorreta.'
                ]);
        }

        // atualiza senha
        $affected = DB::update("
            UPDATE usuarios
            SET senha = ?
            WHERE id = ?
        ", [
            md5($request->input('nova_senha')),
            $userId
        ]);

        return $this->handleAffected(
            $affected,
            'admin.index',
            'Senha alterada com sucesso!',
            'Erro ao alterar a senha.'
        );
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('notify', [
                'type' => 'success',
                'message' => 'Você saiu do sistema.'
            ]);
    }
}
