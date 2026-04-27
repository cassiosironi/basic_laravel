<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Notifies;
use App\Support\SanitizesInput;
use App\Support\AntiBotCaptcha;
use Illuminate\Support\Facades\Storage;
use App\Models\Chamado;


class ChamadoController extends Controller
{
    use Notifies, SanitizesInput, AntiBotCaptcha;

    // =========================
    // SITE - FORM
    // =========================
    public function create()
    {       
        $captcha = $this->captchaGenerate('ticket_captcha');

        return view('site.chamados.create', [
            'captcha' => $captcha
        ]);

    }

    // =========================
    // SITE - STORE
    // =========================        
    public function store(Request $request)
    {
        // 1) Captcha primeiro
        $captchaError = $this->captchaValidate($request, 'ticket_captcha', 3);
        if ($captchaError) {
            return redirect()
                ->route('site.chamados.create')
                ->withInput()
                ->with('notify', [
                    'type' => 'danger',
                    'message' => $captchaError
                ]);
        }

        // 2) Validação do formulário
        $request->validate([
            'titulo' => 'required|string|max:120',
            'descricao' => 'required|string',
            'tipo' => 'required|in:rede,sistema,hardware',
            'anexo' => 'nullable|file|mimes:pdf,jpg,jpeg|max:5120', // 5MB
        ]);

        // 3) Usuário logado (conforme seu fluxo atual)
        $user = session('admin_user');
        if (!$user || !isset($user['id'])) {
            return redirect()
                ->route('admin.login')
                ->with('notify', [
                    'type' => 'warning',
                    'message' => 'Faça login para abrir um chamado.'
                ]);
        }

        // 4) Regra: somente client abre chamado
        if (($user['nivel'] ?? '') !== 'client') {
            return redirect()
                ->route('admin.index')
                ->with('notify', [
                    'type' => 'warning',
                    'message' => 'Apenas usuários client podem abrir chamados.'
                ]);
        }

        // 5) Sanitização
        $titulo = $this->clean($request->input('titulo'));
        $descricao = $this->clean($request->input('descricao'));
        $tipo = $request->input('tipo');

        // 6) Upload do anexo (Storage)
        $anexoPath = null;

        if ($request->hasFile('anexo')) {
            $file = $request->file('anexo');

            if (!$file->isValid()) {
                return $this->backNotify('danger', 'Arquivo de anexo inválido.');
            }

            // Armazena em storage/app/public/chamados
            $anexoPath = $file->store('chamados', 'public'); // ex: chamados/arquivo.pdf
        }

        // 7) Insert do chamado
        DB::insert("
            INSERT INTO chamados
            (titulo, descricao, tipo, anexo, autor_id, status, data_abertura)
            VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
        ", [
            $titulo,
            $descricao,
            $tipo,
            $anexoPath,            // agora é path no storage disk "public"
            (int) $user['id'],
            'em andamento'
        ]);

        // 8) Pega o ID recém inserido
        $id = (int) DB::getPdo()->lastInsertId();

        // 9) Gera numero_chamado: id + ano + random 3 dígitos
        $ano = date('Y');
        $rand = str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
        $numeroChamado = $id . '-' . $ano . '-' . $rand;

        // 10) Atualiza o registro com o numero_chamado
        DB::update("
            UPDATE chamados
            SET numero_chamado = ?
            WHERE id = ?
        ", [$numeroChamado, $id]);

        // 11) Notificação com número
        return redirect()
            ->route('site.chamados.create')
            ->with('notify', [
                'type' => 'success',
                'message' => 'Chamado aberto com sucesso! Nº: ' . $numeroChamado
            ]);


    }
}