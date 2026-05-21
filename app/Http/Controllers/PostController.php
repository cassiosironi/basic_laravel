<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Support\Notifies;
use App\Support\SanitizesInput;
use App\Support\UploadsImages;

class PostController extends Controller
{
    use Notifies, SanitizesInput, UploadsImages;

    // =========================
    // SITE - LISTAR POSTS
    // =========================
    public function siteIndex()
    {
        $posts = DB::select("
            SELECT id, title, slug, cover_image, summary, created_at
            FROM posts
            ORDER BY created_at DESC, id DESC
        ");

        return view('site.blog.index', [
            'posts' => $posts
        ]);
    }

    // =========================
    // SITE - EXIBIR POST
    // =========================
    public function siteShow($slug)
    {
        $rows = DB::select("
            SELECT id, title, slug, cover_image, summary, content, created_at
            FROM posts
            WHERE slug = ?
            LIMIT 1
        ", [$slug]);

        if (!isset($rows[0])) {
            abort(404);
        }

        return view('site.blog.show', [
            'post' => $rows[0]
        ]);
    }

    // =========================
    // ADMIN - INDEX
    // =========================
    public function adminIndex()
    {
        $posts = DB::select("
            SELECT p.id, p.title, p.slug, p.created_at, u.nome as autor_nome
            FROM posts p
            JOIN usuarios u ON u.id = p.author_id
            ORDER BY p.created_at DESC, p.id DESC
        ");

        return view('admin.posts.index', [
            'posts' => $posts
        ]);
    }

    // =========================
    // ADMIN - CREATE
    // =========================
    public function adminCreate()
    {
        return view('admin.posts.create');
    }

    // =========================
    // ADMIN - STORE
    // =========================
    public function adminStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:160',
            'summary' => 'required|string|max:500',
            'content' => 'required|string',
            'cover_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_current' => 'nullable|string|max:255',
        ]);

        // usuário logado (admin/editor)
        $u = session('admin_user');
        if (!$u || !isset($u['id'])) {
            return redirect()->route('admin.login');
        }

        try {
            $title = $this->clean($request->input('title'));
            $summary = $this->clean($request->input('summary'));
            $content = (string) $request->input('content'); // HTML do WYSIWYG (não usar clean)

            // upload capa
            $coverPath = $this->handleImageUpload(
                $request,
                fileInputName: 'cover_file',
                currentPathInput: 'cover_current',
                destPublicSubdir: 'img/posts',
                filenamePrefix: 'postcover',
                allowedExt: ['jpg','jpeg','png','webp'],
                maxKb: 2048,
                deleteOld: false,
                defaultPath: '' // pode ficar null
            );

            if ($coverPath === '') {
                $coverPath = null;
            }

            // insert (slug temporário)
            DB::insert("
                INSERT INTO posts (title, slug, cover_image, summary, content, author_id, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            ", [
                $title,
                'tmp',
                $coverPath,
                $summary,
                $content,
                (int) $u['id']
            ]);

            $id = (int) DB::getPdo()->lastInsertId();

            // slug final = slug(title) + "-" + id
            $base = Str::slug(strip_tags($request->input('title')));
            if ($base === '') {
                $base = 'post';
            }
            $slug = $base . '-' . $id;

            $affected = DB::update("
                UPDATE posts SET slug = ? WHERE id = ?
            ", [$slug, $id]);

            return redirect()
                ->route('posts.index')
                ->withInput()
                ->with('notify', [
                    'type' => 'success',
                    'message' => 'Post criado com sucesso!'
                ]);

        } catch (\Throwable $e) {
            return $this->handleException('danger', 'Erro ao criar post: ' . $e->getMessage());
        }
    }

    // =========================
    // ADMIN - EDIT
    // =========================
    public function adminEdit($id)
    {
        $rows = DB::select("
            SELECT id, title, slug, cover_image, summary, content
            FROM posts
            WHERE id = ?
            LIMIT 1
        ", [$id]);

        if (!isset($rows[0])) {
            abort(404);
        }

        return view('admin.posts.edit', [
            'post' => $rows[0]
        ]);
    }

    // =========================
    // ADMIN - UPDATE
    // =========================
    public function adminUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:160',
            'summary' => 'required|string|max:500',
            'content' => 'required|string',
            'cover_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_current' => 'nullable|string|max:255',
        ]);

        try {
            $title = $this->clean($request->input('title'));
            $summary = $this->clean($request->input('summary'));
            $content = (string) $request->input('content'); // HTML do WYSIWYG

            $coverPath = $this->handleImageUpload(
                $request,
                fileInputName: 'cover_file',
                currentPathInput: 'cover_current',
                destPublicSubdir: 'img/posts',
                filenamePrefix: 'postcover',
                allowedExt: ['jpg','jpeg','png','webp'],
                maxKb: 2048,
                deleteOld: true,
                defaultPath: $request->input('cover_current')
            );

            // recalcula slug com base no título + id
            $base = Str::slug(strip_tags($request->input('title')));
            if ($base === '') {
                $base = 'post';
            }
            $slug = $base . '-' . (int)$id;

            $affected = DB::update("
                UPDATE posts
                SET title = ?, slug = ?, cover_image = ?, summary = ?, content = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ", [
                $title,
                $slug,
                $coverPath !== '' ? $coverPath : null,
                $summary,
                $content,
                (int)$id
            ]);

            return $this->handleAffected(
                (int)$affected,
                'admin.posts.index',
                'Post atualizado com sucesso!',
                'Nenhuma alteração aplicada.'
            );

        } catch (\Throwable $e) {
            return $this->handleException('danger', 'Erro ao atualizar post: ' . $e->getMessage());
        }
    }

    // =========================
    // ADMIN - DELETE
    // =========================
    public function adminDestroy($id)
    {
        try {
            $affected = DB::delete("DELETE FROM posts WHERE id = ?", [(int)$id]);

            return $this->handleAffected(
                (int)$affected,
                'admin.posts.index',
                'Post excluído com sucesso!',
                'Erro ao excluir post.'
            );

        } catch (\Throwable $e) {
            return $this->handleException('danger', 'Erro ao excluir post: ' . $e->getMessage());
        }
    }
}
