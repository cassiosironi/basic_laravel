<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sobre;
use App\Support\Notifies;
use App\Support\SanitizesInput;

class SobreHomeController extends Controller
{
    use Notifies, SanitizesInput;

    // =========================
    // ADMIN - EDIT (singleton)
    // =========================
    public function adminEdit()
    {
        $rows = DB::select("
            SELECT id, image, title, text
            FROM sobre
            ORDER BY id ASC
            LIMIT 1
        ");

        $sobre = isset($rows[0]) ? $rows[0] : null;

        // Se não existir ainda, cria um registro default (opcional, mas útil)
        if (!$sobre) {
            DB::statement("
                INSERT INTO sobre (image, title, text)
                VALUES (?, ?, ?)
            ", ['img/sobre/default.jpg', 'Sobre nós', 'Texto institucional...']);

            $rows = DB::select("
                SELECT id, image, title, text
                FROM sobre
                ORDER BY id ASC
                LIMIT 1
            ");
            $sobre = isset($rows[0]) ? $rows[0] : null;
        }

        return view('admin.sobre.edit', [
            'sobre' => $sobre
        ]);
    }

    // =========================
    // ADMIN - UPDATE (singleton)
    // =========================
    public function adminUpdate(Request $request)
    {
        $request->validate([
            'image' => 'required|string|max:255',
            'title' => 'required|string|max:120',
            'text'  => 'nullable|string|max:5000',
        ]);

        // pega o primeiro registro como alvo
        $rows = DB::select("SELECT id FROM sobre ORDER BY id ASC LIMIT 1");
        $id = isset($rows[0]) ? (int)$rows[0]->id : 0;

        if ($id <= 0) {            
            $affected = DB::insert("
                INSERT INTO sobre (image, title, text)
                VALUES (?, ?, ?)
            ", [
                $request->input('image'),
                $request->input('title'),
                $request->input('text')
            ]);

            $affected = $affected ? 1 : 0;

            return $this->handleAffected(
                $affected,
                'admin.sobre.edit',
                'Conteúdo Sobre salvo com sucesso!',
                'Erro ao salvar o conteúdo Sobre!'
            );
        }

       
        $affected = DB::update("
            UPDATE sobre
            SET image = ?, title = ?, text = ?
            WHERE id = ?
        ", [
            $request->input('image'),
            $request->input('title'),
            $request->input('text'),
            $id
        ]);

        return $this->handleAffected(
            $affected,
            'admin.sobre.edit',
            'Conteúdo Sobre atualizado com sucesso!',
            'Erro ao atualizar o conteúdo Sobre!'
        );

    }
}
