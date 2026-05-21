<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Notifies;
use App\Support\SanitizesInput;
use App\Support\AntiBotCaptcha;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AdminAuthController extends Controller
{
    use Notifies, SanitizesInput, AntiBotCaptcha;

    public function showLogin()
    {        
        $captcha = $this->captchaGenerate('login_captcha');

        return view('admin.auth.login', [
            'captcha' => $captcha
        ]);

    }
   
    public function login(Request $request)
    {
        // captcha
        $captchaError = $this->captchaValidate($request, 'login_captcha', 3);
        if ($captchaError) {
            return redirect()
                ->route('admin.login')
                ->withInput()
                ->with('notify', [
                    'type' => 'danger',
                    'message' => $captchaError
                ]);
        }

        $request->validate([
            'login' => 'required|string|max:80',
            'senha' => 'required|string|max:120',
        ]);

        try {
            $login = $this->clean($request->input('login'));
            $senhaInformada = (string) $request->input('senha'); // NÃO limpar com htmlspecialchars

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

            if ((int) $user->ativo !== 1) {
                return redirect()
                    ->route('admin.login')
                    ->withInput()
                    ->with('notify', [
                        'type' => 'warning',
                        'message' => 'Usuário inativo. Contate o administrador.'
                    ]);
            }

            $hashDoBanco = (string) $user->senha;

            // 1) Primeiro tenta Hash nativo do Laravel
            $ok = Hash::check($senhaInformada, $hashDoBanco);

            // 2) Fallback: se ainda estiver em MD5 (32 hex), valida em MD5 e migra para Hash
            if (!$ok) {
                $pareceMd5 = (bool) preg_match('/^[a-f0-9]{32}$/i', $hashDoBanco);
                if ($pareceMd5 && md5($senhaInformada) === $hashDoBanco) {
                    $ok = true;

                    // Migra automaticamente para Hash forte
                    DB::update("
                        UPDATE usuarios SET senha = ? WHERE id = ?
                    ", [Hash::make($senhaInformada), (int)$user->id]);
                }
            }

            if (!$ok) {
                return redirect()
                    ->route('admin.login')
                    ->withInput()
                    ->with('notify', [
                        'type' => 'danger',
                        'message' => 'Senha inválida.'
                    ]);
            }

            // Login OK
            $request->session()->regenerate();

            session([
                'admin_user' => [
                    'id'    => (int) $user->id,
                    'nome'  => (string) $user->nome,
                    'login' => (string) $user->login,
                    'nivel' => (string) $user->nivel
                ],
                'just_logged_in' => 1
            ]);

            // client vai para abertura de chamado
            if ($user->nivel === 'client') {
                return redirect()
                    ->route('site.chamados.create')
                    ->with('notify', [
                        'type' => 'info',
                        'message' => 'Abra seu chamado abaixo.'
                    ]);
            }

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

        $hashDoBanco = (string) $rows[0]->senha;
        $senhaAtualInformada = (string) $request->input('senha_atual');

        // aceita tanto Hash forte quanto MD5 (para transição)
        $ok = Hash::check($senhaAtualInformada, $hashDoBanco);

        if (!$ok) {
            $pareceMd5 = (bool) preg_match('/^[a-f0-9]{32}$/i', $hashDoBanco);
            if ($pareceMd5 && md5($senhaAtualInformada) === $hashDoBanco) {
                $ok = true; // permite trocar mesmo se o banco ainda está em md5
            }
        }

        if (!$ok) {
            return redirect()
                ->route('admin.perfil.senha')
                ->with('notify', [
                    'type' => 'danger',
                    'message' => 'Senha atual incorreta.'
                ]);
        }

        $affected = DB::update("
            UPDATE usuarios
            SET senha = ?
            WHERE id = ?
        ", [
            Hash::make((string) $request->input('nova_senha')),
            $userId
        ]);

        return $this->handleAffected(
            (int)$affected,
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
