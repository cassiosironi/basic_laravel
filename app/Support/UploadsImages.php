<?php

namespace App\Support;

use Illuminate\Http\Request;

trait UploadsImages
{
    /**
     * Faz upload de imagem para /public e devolve o path para gravar no DB (ex: img/sobre/abc.jpg)
     *
     * @param Request $request
     * @param string $fileInputName       Nome do input file (ex: image_file)
     * @param string $currentPathInput    Nome do hidden com path atual (ex: image_current)
     * @param string $destPublicSubdir    Subpasta em public/ (ex: img/sobre)
     * @param string $filenamePrefix      Prefixo do nome (ex: sobre, banner)
     * @param array  $allowedExt          Extensões permitidas (ex: ['jpg','jpeg','png','webp'])
     * @param int    $maxKb               Tamanho máximo em KB (ex: 2048 = 2MB)
     * @param bool   $deleteOld           Se true, apaga imagem anterior (quando existir e for diferente)
     * @param string $defaultPath         Path default caso não exista current (ex: img/sobre/default.jpg)
     *
     * @return string Path final para o DB
     *
     * @throws \RuntimeException em caso de arquivo inválido
     */
    protected function handleImageUpload(
        Request $request,
        string $fileInputName = 'image_file',
        string $currentPathInput = 'image_current',
        string $destPublicSubdir = 'img/uploads',
        string $filenamePrefix = 'img',
        array $allowedExt = ['jpg', 'jpeg', 'png', 'webp'],
        int $maxKb = 2048,
        bool $deleteOld = false,
        string $defaultPath = ''
    ): string {
        // 1) path atual (ou default)
        $currentPath = (string) $request->input($currentPathInput, '');
        $currentPath = $currentPath !== '' ? ltrim($currentPath, '/') : '';
        $finalPath   = $currentPath !== '' ? $currentPath : $defaultPath;

        // 2) se não veio arquivo, retorna o path atual/default
        if (!$request->hasFile($fileInputName)) {
            if ($finalPath === '') {
                throw new \RuntimeException('Nenhuma imagem definida (atual ou default).');
            }
            return $finalPath;
        }

        $file = $request->file($fileInputName);

        if (!$file || !$file->isValid()) {
            throw new \RuntimeException('Arquivo de imagem inválido.');
        }

        // 3) valida extensão e tamanho (extra, além do validate() do Laravel)
        $ext = strtolower((string) $file->getClientOriginalExtension());
        if (!in_array($ext, $allowedExt, true)) {
            throw new \RuntimeException('Extensão não permitida: ' . $ext);
        }

        $sizeKb = (int) round($file->getSize() / 1024);
        if ($sizeKb > $maxKb) {
            throw new \RuntimeException('Arquivo excede o tamanho máximo permitido (' . $maxKb . 'KB).');
        }

        // 4) garante pasta em public/
        $destPublicSubdir = trim($destPublicSubdir, '/'); // ex: img/sobre
        $destAbs = public_path($destPublicSubdir);

        if (!is_dir($destAbs)) {
            @mkdir($destAbs, 0775, true);
        }

        if (!is_dir($destAbs)) {
            throw new \RuntimeException('Não foi possível criar o diretório de upload: ' . $destAbs);
        }

        // 5) nome seguro/único
        $name = $filenamePrefix
            . '_' . date('Ymd_His')
            . '_' . bin2hex(random_bytes(4))
            . '.' . $ext;

        // 6) move para public/<dest>
        $file->move($destAbs, $name);

        // 7) path final salvo no banco
        $newPath = $destPublicSubdir . '/' . $name; // ex: img/sobre/sobre_2026...jpg

        // 8) opcional: remove imagem antiga (evitar lixo)
        if ($deleteOld && $currentPath !== '' && $currentPath !== $newPath) {
            $oldAbs = public_path(ltrim($currentPath, '/'));
            if (is_file($oldAbs)) {
                @unlink($oldAbs);
            }
        }

        return $newPath;
    }
}