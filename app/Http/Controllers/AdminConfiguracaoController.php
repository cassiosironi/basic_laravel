<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Notifies;
use App\Support\SanitizesInput;
use App\Support\UploadsImages;

class AdminConfiguracaoController extends Controller
{
    use Notifies, SanitizesInput, UploadsImages;

    // =========================
    // ADMIN - EDIT (singleton)
    // =========================
    public function edit()
    {
        $rows = DB::select("SELECT * FROM configuracoes LIMIT 1");
        $config = isset($rows[0]) ? $rows[0] : null;

        // cria registro inicial se não existir
        if (!$config) {
            DB::statement("
                INSERT INTO configuracoes
                (nome_sistema, logo, favicon)
                VALUES (?, ?, ?)
            ", [
                'Nome do Sistema',
                'img/logo.png',
                'img/favicon.ico'
            ]);

            $rows = DB::select("SELECT * FROM configuracoes LIMIT 1");
            $config = $rows[0];
        }

        return view('admin.configuracoes.edit', [
            'config' => $config
        ]);
    }

    // =========================
    // ADMIN - UPDATE
    // =========================
    
    public function update(Request $request)
    {
        $request->validate([
            'nome_sistema' => 'required|string|max:120',

            // uploads
            'logo_file' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'favicon_file' => 'nullable|image|mimes:png,ico|max:1024',
            'email_header_file' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',

            // paths atuais
            'logo_current' => 'required|string|max:255',
            'favicon_current' => 'required|string|max:255',
            'email_header_current' => 'nullable|string|max:255',

            // demais campos
            'email_contato' => 'nullable|email|max:120',
            'email_smtp' => 'nullable|email|max:120',
            'telefone_sac' => 'nullable|string|max:40',
            'telefone_whatsapp' => 'nullable|string|max:40',
            'link_facebook' => 'nullable|url|max:255',
            'link_instagram' => 'nullable|url|max:255',
            'link_linkedin' => 'nullable|url|max:255',
            'link_youtube' => 'nullable|url|max:255',
            'endereco' => 'nullable|string|max:255',
            'horario_atendimento' => 'nullable|string|max:255',
            'mapa_iframe' => 'nullable|string|max:5000',
        ]);

        try {
            // === uploads padronizados ===
            $logoPath = $this->handleImageUpload(
                $request,
                fileInputName: 'logo_file',
                currentPathInput: 'logo_current',
                destPublicSubdir: 'img/config',
                filenamePrefix: 'logo',
                allowedExt: ['png','jpg','jpeg','webp'],
                maxKb: 2048,
                deleteOld: true,
                defaultPath: $request->input('logo_current')
            );

            $faviconPath = $this->handleImageUpload(
                $request,
                fileInputName: 'favicon_file',
                currentPathInput: 'favicon_current',
                destPublicSubdir: 'img/config',
                filenamePrefix: 'favicon',
                allowedExt: ['png','ico'],
                maxKb: 1024,
                deleteOld: true,
                defaultPath: $request->input('favicon_current')
            );

            $emailHeaderPath = $this->handleImageUpload(
                $request,
                fileInputName: 'email_header_file',
                currentPathInput: 'email_header_current',
                destPublicSubdir: 'img/config',
                filenamePrefix: 'email_header',
                allowedExt: ['png','jpg','jpeg','webp'],
                maxKb: 2048,
                deleteOld: true,
                defaultPath: $request->input('email_header_current')
            );

            $row = DB::select("SELECT id FROM configuracoes LIMIT 1");
            $id = (int) $row[0]->id;

            DB::update("
                UPDATE configuracoes SET
                  nome_sistema = ?,
                  email_contato = ?,
                  email_smtp = ?,
                  logo = ?,
                  favicon = ?,
                  email_header_image = ?,
                  telefone_sac = ?,
                  telefone_whatsapp = ?,
                  link_facebook = ?,
                  link_instagram = ?,
                  link_linkedin = ?,
                  link_youtube = ?,
                  endereco = ?,
                  horario_atendimento = ?,
                  mapa_iframe = ?
                WHERE id = ?
            ", [
                $this->clean($request->input('nome_sistema')),
                $request->input('email_contato'),
                $request->input('email_smtp'),
                $logoPath,
                $faviconPath,
                $emailHeaderPath,
                $this->clean($request->input('telefone_sac')),
                $this->clean($request->input('telefone_whatsapp')),
                $request->input('link_facebook'),
                $request->input('link_instagram'),
                $request->input('link_linkedin'),
                $request->input('link_youtube'),
                $this->clean($request->input('endereco')),
                $this->clean($request->input('horario_atendimento')),
                $request->input('mapa_iframe'),
                $id
            ]);

            return redirect()
                ->route('admin.configuracoes.edit')
                ->with('notify', [
                    'type' => 'success',
                    'message' => 'Configurações atualizadas com sucesso!'
            ]);

        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.configuracoes.edit')
                ->with('notify', [
                    'type' => 'danger',
                    'message' => 'Erro ao salvar configurações: '.$e->getMessage()
                ]);
        }
    }
}