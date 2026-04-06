<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Notifies;
use App\Models\Banners;

class BannerController extends Controller
{

    use Notifies;

    // =========================
    // SITE
    // =========================

    public function siteHome()
    {
        // SELECT precisa de DB::select para retornar resultados
        $banners = DB::select("
            SELECT id, image, title, subtitle
            FROM banners
            ORDER BY id DESC
        ");
            
        $sobre_rows = DB::select("
            SELECT id, image, title, text
            FROM sobre
            ORDER BY id ASC
            LIMIT 1
        ");
        $sobre = isset($sobre_rows[0]) ? $sobre_rows[0] : null;

        return view('site.home', [
            'banners' => $banners,
            'sobre'   => $sobre
        ]);

    }

    // =========================
    // ADMIN - INDEX
    // =========================

    public function adminIndex()
    {
        $banners = DB::select("
            SELECT id, image, title, subtitle
            FROM banners
            ORDER BY id DESC
        ");

        return view('admin.banners.index', [
            'banners' => $banners
        ]);
    }

    // =========================
    // ADMIN - SHOW
    // =========================

    public function adminShow($id)
    {
        $rows = DB::select("
            SELECT id, image, title, subtitle
            FROM banners
            WHERE id = ?
            LIMIT 1
        ", [$id]);

        $banner = isset($rows[0]) ? $rows[0] : null;

        if (!$banner) {
            abort(404);
        }

        return view('admin.banners.show', [
            'banner' => $banner
        ]);
    }

    // =========================
    // ADMIN - CREATE
    // =========================

    public function adminCreate()
    {
        return view('admin.banners.create');
    }

    public function adminStore(Request $request)
    {
        // Validação simples (sem FormRequest, só estudo)
        $request->validate([
            'image' => 'required|string|max:255',
            'title' => 'required|string|max:120',
            'subtitle' => 'nullable|string|max:255',
        ]);
        
        $affected = DB::insert("
            INSERT INTO banners (image, title, subtitle)
            VALUES (?, ?, ?)
        ", [$image, $title, $subtitle]);

        // DB::insert retorna boolean → normalize:
        $affected = $affected ? 1 : 0;

        return $this->handleAffected(
            $affected,
            'admin.banners.index',
            'Criado com sucesso!',
            'Erro ao criar banner!'
        );
    }

    // =========================
    // ADMIN - EDIT
    // =========================

    public function adminEdit($id)
    {
        $rows = DB::select("
            SELECT id, image, title, subtitle
            FROM banners
            WHERE id = ?
            LIMIT 1
        ", [$id]);

        $banner = isset($rows[0]) ? $rows[0] : null;

        if (!$banner) {
            abort(404);
        }

        return view('admin.banners.edit', [
            'banner' => $banner
        ]);
    }

    public function adminUpdate(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|string|max:255',
            'title' => 'required|string|max:120',
            'subtitle' => 'nullable|string|max:255',
        ]);

        try {
            $affected = DB::update("
                UPDATE banners
                SET image = ?, title = ?, subtitle = ?
                WHERE id = ?
            ", [
                $request->input('image'),
                $request->input('title'),
                $request->input('subtitle'),
                $id
            ]);

            return $this->handleAffected(
                $affected,
                'admin.banners.index',
                'Atualizado com sucesso!',
                'Erro ao atualizar o banner!'
            );

        } catch (\Throwable $e) {
            // opcional: \Log::error($e->getMessage());
            return $this->handleException('Erro inesperado ao atualizar o banner.');
        }

    }

    // =========================
    // ADMIN - DELETE
    // =========================

    public function adminDestroy(Request $request, $id)
    {        
        $affected = DB::delete("
            DELETE FROM banners WHERE id = ?
        ", [$id]);

        return $this->handleAffected(
            $affected,
            'admin.banners.index',
            'Excluído com sucesso!',
            'Erro ao excluir banner!'
        );
    }
}