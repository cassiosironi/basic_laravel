<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SobreHomeController extends Controller
{
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
            // fallback: cria se não existir
            DB::statement("
                INSERT INTO sobre (image, title, text)
                VALUES (?, ?, ?)
            ", [
                $request->input('image'),
                $request->input('title'),
                $request->input('text')
            ]);

            return redirect()->route('admin.sobre.edit');
        }

        DB::statement("
            UPDATE sobre
            SET image = ?, title = ?, text = ?
            WHERE id = ?
        ", [
            $request->input('image'),
            $request->input('title'),
            $request->input('text'),
            $id
        ]);

        return redirect()->route('admin.sobre.edit');
    }
}
