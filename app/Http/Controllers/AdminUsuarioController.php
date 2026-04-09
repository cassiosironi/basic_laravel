<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Notifies;
use App\Support\SanitizesInput;
use App\Models\Usuario;

class AdminUsuarioController extends Controller
{
    use Notifies, SanitizesInput;

    // LISTAGEM
    public function index()
    {
        $usuarios = DB::select("
            SELECT id, nome, login, nivel, ativo
            FROM usuarios
            ORDER BY nome ASC
        ");

        return view('admin.usuarios.index', [
            'usuarios' => $usuarios
        ]);
    }

    // FORM CREATE
    public function create()
    {
        return view('admin.usuarios.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'nome'  => 'required|string|max:120',
            'login' => 'required|string|max:80',
            'nivel' => 'required|in:admin,editor,client',
            'senha' => 'required|string|min:3',
            'ativo' => 'required|in:0,1',
        ]);

        try {
            $nome  = $this->clean($request->input('nome'));
            $login = $this->clean($request->input('login'));
            $nivel = $request->input('nivel');
            $ativo = (int)$request->input('ativo');
            $senha = md5($request->input('senha'));

            $affected = DB::insert("
                INSERT INTO usuarios (nome, login, nivel, senha, ativo, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ", [$nome, $login, $nivel, $senha, $ativo]);

            $affected = $affected ? 1 : 0;

            return $this->handleAffected(
                $affected,
                'admin.usuarios.index',
                'Usuário criado com sucesso!',
                'Erro ao criar usuário.'
            );

        } catch (\Throwable $e) {
            return $this->handleException('Erro inesperado ao criar usuário.');
        }
    }

    // FORM EDIT
    public function edit($id)
    {
        $rows = DB::select("
            SELECT id, nome, login, nivel, ativo
            FROM usuarios
            WHERE id = ?
            LIMIT 1
        ", [$id]);

        if (!isset($rows[0])) {
            abort(404);
        }

        return view('admin.usuarios.edit', [
            'usuario' => $rows[0]
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome'  => 'required|string|max:120',
            'nivel' => 'required|in:admin,editor,client',
            'ativo' => 'required|in:0,1',
            'senha' => 'nullable|string|min:3',
        ]);

        try {
            $nome  = $this->clean($request->input('nome'));
            $nivel = $request->input('nivel');
            $ativo = (int)$request->input('ativo');

            if ($request->filled('senha')) {
                $senha = md5($request->input('senha'));

                $affected = DB::update("
                    UPDATE usuarios
                    SET nome = ?, nivel = ?, ativo = ?, senha = ?
                    WHERE id = ?
                ", [$nome, $nivel, $ativo, $senha, $id]);
            } else {
                $affected = DB::update("
                    UPDATE usuarios
                    SET nome = ?, nivel = ?, ativo = ?
                    WHERE id = ?
                ", [$nome, $nivel, $ativo, $id]);
            }

            return $this->handleAffected(
                $affected,
                'admin.usuarios.index',
                'Usuário atualizado com sucesso!',
                'Erro ao atualizar usuário.'
            );

        } catch (\Throwable $e) {
            return $this->handleException('Erro inesperado ao atualizar usuário.');
        }
    }

    // DELETE
    public function delete($id)
    {
        // proteção: não deletar a si mesmo
        $current = session('admin_user');
        if ($current && $current['id'] == $id) {
            return $this->backNotify('warning', 'Você não pode excluir seu próprio usuário.');
        }

        try {
            $affected = DB::delete("DELETE FROM usuarios WHERE id = ?", [$id]);

            return $this->handleAffected(
                $affected,
                'admin.usuarios.index',
                'Usuário removido com sucesso!',
                'Erro ao remover usuário.'
            );

        } catch (\Throwable $e) {
            return $this->handleException('Erro inesperado ao remover usuário.');
        }
    }
    // LOGS DE LOGIN DO USUÁRIO
    public function logs($id)
    {
        // busca usuário
        $u = DB::select("
            SELECT id, nome, login
            FROM usuarios
            WHERE id = ?
            LIMIT 1
        ", [$id]);

        if (!isset($u[0])) {
            abort(404);
        }

        // busca logs
        $logs = DB::select("
            SELECT id, ip, user_agent, created_at
            FROM admin_login_logs
            WHERE usuario_id = ?
            ORDER BY created_at DESC
        ", [$id]);

        return view('admin.usuarios.logs', [
            'usuario' => $u[0],
            'logs'    => $logs
        ]);
    }
}
