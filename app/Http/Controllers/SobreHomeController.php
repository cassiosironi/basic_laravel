<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sobre;
use App\Support\Notifies;
use App\Support\SanitizesInput;
use App\Support\UploadsImages;


class SobreHomeController extends Controller
{
    use Notifies, SanitizesInput, UploadsImages;

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
            'title' => 'required|string|max:120',
            'text'  => 'nullable|string|max:5000',

            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB
            'image_current' => 'nullable|string|max:255',
        ]);

        $title = $this->clean($request->input('title'));
        $text  = $request->input('text');      
       
        $rows = DB::select("SELECT id FROM sobre ORDER BY id ASC LIMIT 1");
        $id = isset($rows[0]) ? (int) $rows[0]->id : 0;
            
        try {            

            $imagePath = $this->handleImageUpload(
                $request,
                fileInputName: 'image_file',
                currentPathInput: 'image_current',
                destPublicSubdir: 'img/sobre',
                filenamePrefix: 'sobre',
                allowedExt: ['jpg','jpeg','png','webp'],
                maxKb: 2048,
                deleteOld: true,
                defaultPath: 'img/sobre/sobre1.jpg'
            );

            if ($id <= 0) {
                $ok = DB::insert("
                    INSERT INTO sobre (image, title, text)
                    VALUES (?, ?, ?)
                ", [$imagePath, $title, $text]);

                $affected = $ok ? 1 : 0;

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
            ", [$imagePath, $title, $text, $id]);

            return $this->handleAffected(
                $affected,
                'admin.sobre.edit',
                'Conteúdo Sobre atualizado com sucesso!',
                'Erro ao atualizar o conteúdo Sobre!'
            );

        } catch (\Throwable $e) {
            return $this->backNotify('danger', 'Falha ao salvar: ' . $e->getMessage());
        }
    }
}
