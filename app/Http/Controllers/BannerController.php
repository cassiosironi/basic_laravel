<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Banners;
use App\Support\Notifies;
use App\Support\SanitizesInput;
use App\Support\UploadsImages;
use App\Support\ReordersRecords;


class BannerController extends Controller
{

    use Notifies, SanitizesInput, UploadsImages, ReordersRecords;

    // =========================
    // SITE
    // =========================

    public function siteHome()
    {
        // SELECT precisa de DB::select para retornar resultados
        $banners = DB::select("
            SELECT id, image, title, subtitle, ordem
            FROM banners
            ORDER BY ordem ASC, id ASC
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
            SELECT id, image, title, subtitle, ordem
            FROM banners
            ORDER BY ordem ASC, id ASC
        ");

        return view('admin.banners.index', [
            'banners' => $banners
        ]);
    }

    // =========================
    // CREATE
    // =========================

    public function adminCreate()
    {
        return view('admin.banners.create');
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:120',
            'subtitle' => 'nullable|string|max:255',
            
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB
            'image_current' => 'nullable|string|max:255',
        ]);

        $title = $this->clean($request->input('title'));
        $subtitle = $this->clean($request->input('subtitle'));
        
        $image = $this->handleImageUpload(
            $request,
            fileInputName: 'image_file',
            currentPathInput: 'image_current',
            destPublicSubdir: 'img/banners',
            filenamePrefix: 'banner',
            allowedExt: ['jpg','jpeg','png','webp'],
            maxKb: 2048,
            deleteOld: true,
            defaultPath: 'img/banners/default.png'
        );
        
        $max = DB::select("SELECT COALESCE(MAX(ordem),0) as m FROM banners");
        $next = ((int)$max[0]->m) + 1;


        $affected = DB::insert("
                INSERT INTO banners (image, title, subtitle, ordem)
                VALUES (?, ?, ?, ?)
            ", [$image, $title, $subtitle, $next]);


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
    // EDIT
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
            'title' => 'required|string|max:120',
            'subtitle' => 'nullable|string|max:255',

            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB
            'image_current' => 'nullable|string|max:255',
        ]);

        try {            
            $title = $this->clean($request->input('title'));
            $subtitle = $this->clean($request->input('subtitle'));
                
            $image = $this->handleImageUpload(
                $request,
                fileInputName: 'image_file',
                currentPathInput: 'image_current',
                destPublicSubdir: 'img/banners',
                filenamePrefix: 'banner',
                allowedExt: ['jpg','jpeg','png','webp'],
                maxKb: 2048,
                deleteOld: true,
                defaultPath: 'img/banners/default.png'
            );


            $affected = DB::update("
                UPDATE banners
                SET image = ?, title = ?, subtitle = ?
                WHERE id = ?
            ", [$image, $title, $subtitle, $id]);

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
    // REORDER
    // =========================    
    public function adminReorder()
    {
        $banners = DB::select("
            SELECT id, image, title, subtitle, ordem
            FROM banners
            ORDER BY ordem ASC, id ASC
        ");

        return view('admin.banners.reorder', [
            'banners' => $banners
        ]);
    }

    public function adminReorderSave(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer'
        ]);

        try {
            $affected = $this->applyOrder('banners', $request->input('order'), 'id', 'ordem', 1);

            // aqui você pode usar notify
            return $this->redirectNotify(
                'admin.banners.index',
                'success',
                'Ordem atualizada com sucesso!'
            );

        } catch (\Throwable $e) {
            return $this->handleException('Falha ao reordenar');
        }
    }


    // =========================
    // DELETE
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