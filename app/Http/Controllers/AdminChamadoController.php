<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Notifies;
use App\Support\SanitizesInput;

class AdminChamadoController extends Controller
{
    use Notifies, SanitizesInput;

    // =========================
    // ADMIN - INDEX (lista chamados)
    // =========================
    public function index(Request $request)
    {
        // opcional: filtro por status via querystring (?status=em%20andamento)
        $status = $request->input('status', '');

        $sql = "
            SELECT c.id, c.numero_chamado, c.titulo, c.tipo, c.status, c.data_abertura, c.data_conclusao,
                   u.nome as autor_nome, u.login as autor_login
            FROM chamados c
            JOIN usuarios u ON u.id = c.autor_id
            WHERE 1 = 1
        ";

        $params = [];

        // se quiser listar apenas abertos por padrão:
        // $sql .= " AND c.status = ? ";
        // $params[] = 'em andamento';

        if ($status !== '') {
            $sql .= " AND c.status = ? ";
            $params[] = $status;
        }

        $sql .= " ORDER BY c.data_abertura DESC, c.id DESC ";

        $chamados = DB::select($sql, $params);

        return view('admin.chamados.index', [
            'chamados' => $chamados,
            'statusFiltro' => $status
        ]);
    }

    // =========================
    // ADMIN - EDIT
    // =========================
    public function edit($id)
    {
        $rows = DB::select("
            SELECT c.*,
                   u.nome as autor_nome, u.login as autor_login, u.nivel as autor_nivel
            FROM chamados c
            JOIN usuarios u ON u.id = c.autor_id
            WHERE c.id = ?
            LIMIT 1
        ", [$id]);

        if (!isset($rows[0])) {
            abort(404);
        }

        return view('admin.chamados.edit', [
            'chamado' => $rows[0]
        ]);
    }

    // =========================
    // ADMIN - UPDATE (apenas status/orientacoes/data_conclusao)
    // =========================
    public function update(Request $request, $id)
    {
        // validações: data_conclusao não pode ser > hoje
        $request->validate([
            'status' => 'required|in:em andamento,resolvido',
            'orientacoes' => 'nullable|string|max:5000',
            'data_conclusao' => 'nullable|date|before_or_equal:today',
        ]);

        $status = $request->input('status');
        $orientacoes = $this->clean($request->input('orientacoes'));

        // data_conclusao:
        // - se status = resolvido e data_conclusao vier vazia -> set CURRENT_TIMESTAMP
        // - se status = em andamento -> data_conclusao = NULL
        $dataConclusao = $request->input('data_conclusao');

        try {
            if ($status === 'resolvido') {
                if ($dataConclusao === null || trim($dataConclusao) === '') {
                    // salva "agora"
                    $affected = DB::update("
                        UPDATE chamados
                        SET status = ?, orientacoes = ?, data_conclusao = CURRENT_TIMESTAMP
                        WHERE id = ?
                    ", [$status, $orientacoes, $id]);
                } else {
                    // salva a data informada (validada <= hoje)
                    $affected = DB::update("
                        UPDATE chamados
                        SET status = ?, orientacoes = ?, data_conclusao = ?
                        WHERE id = ?
                    ", [$status, $orientacoes, $dataConclusao, $id]);
                }
            } else {
                // em andamento -> limpa conclusão
                $affected = DB::update("
                    UPDATE chamados
                    SET status = ?, orientacoes = ?, data_conclusao = NULL
                    WHERE id = ?
                ", [$status, $orientacoes, $id]);
            }

            return $this->handleAffected(
                (int)$affected,
                'admin.chamados.index',
                'Chamado atualizado com sucesso!',
                'Nenhuma alteração aplicada.'
            );

        } catch (\Throwable $e) {
            return $this->backNotify('danger', 'Erro ao atualizar chamado: ' . $e->getMessage());
        }
    }
}