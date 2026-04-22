<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Notifies;
use App\Support\SanitizesInput;
use App\Models\Chamado;


class ChamadoController extends Controller
{
    use Notifies, SanitizesInput;

    // =========================
    // SITE - FORM
    // =========================
    public function create()
    {
        return view('site.chamados.create');
    }

    // =========================
    // SITE - STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:120',
            'descricao' => 'required|string',
            'tipo' => 'required|in:rede,sistema,hardware',
            'anexo' => 'nullable|file|mimes:pdf,jpg,jpeg|max:5120', // 5MB
        ]);

        // usuário logado no site
        $user = session('site_user');
        if (!$user || !isset($user['id'])) {
            return redirect()
                ->route('site.login')
                ->with('notify', [
                    'type' => 'warning',
                    'message' => 'Faça login para abrir um chamado.'
                ]);
        }

        // sanitização
        $titulo = $this->clean($request->input('titulo'));
        $descricao = $this->clean($request->input('descricao'));
        $tipo = $request->input('tipo');

        // upload do anexo (opcional)
        $anexoPath = null;

        if ($request->hasFile('anexo')) {
            $file = $request->file('anexo');

            if (!$file->isValid()) {
                return $this->backNotify('danger', 'Arquivo de anexo inválido.');
            }

            $dest = public_path('anexos/chamados');
            if (!is_dir($dest)) {
                @mkdir($dest, 0775, true);
            }

            $ext = strtolower($file->getClientOriginalExtension());
            $name = 'chamado_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

            $file->move($dest, $name);
            $anexoPath = 'anexos/chamados/' . $name;
        }

        DB::insert("
            INSERT INTO chamados
              (titulo, descricao, tipo, anexo, autor_id, status, data_abertura)
            VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
        ", [
            $titulo,
            $descricao,
            $tipo,
            $anexoPath,
            (int) $user['id'],
            'em andamento'
        ]);

        return $this->redirectNotify(
            'site.chamados.create',
            'success',
            'Chamado aberto com sucesso!'
        );
    }
}