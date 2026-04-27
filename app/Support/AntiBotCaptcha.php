<?php

namespace App\Support;

use Illuminate\Http\Request;

trait AntiBotCaptcha
{
    /**
     * Gera desafio simples (soma) e grava na session.
     * Retorna os números para exibir na view.
     */
    protected function captchaGenerate(string $key = 'captcha'): array
    {
        $a = random_int(1, 9);
        $b = random_int(1, 9);

        session([
            "{$key}.answer" => ($a + $b),
            "{$key}.ts"     => time(), // usado para tempo mínimo
        ]);

        return ['a' => $a, 'b' => $b];
    }

    /**
     * Valida captcha + honeypot + tempo mínimo.
     * Se inválido, retorna string com erro; se ok, retorna null.
     */
    protected function captchaValidate(Request $request, string $key = 'captcha', int $minSeconds = 3): ?string
    {
        // 1) Honeypot: bots costumam preencher
        $honeypot = (string) $request->input('website', '');
        if (trim($honeypot) !== '') {
            return 'Validação anti-robô falhou (honeypot).';
        }

        // 2) Tempo mínimo entre render e submit (anti-bot)
        $ts = (int) session("{$key}.ts", 0);
        if ($ts > 0 && (time() - $ts) < $minSeconds) {
            return 'Envio muito rápido. Tente novamente.';
        }

        // 3) Captcha soma
        $expected = (int) session("{$key}.answer", -1);
        $given    = (string) $request->input('captcha', '');

        // remove da sessão para não reutilizar
        session()->forget(["{$key}.answer", "{$key}.ts"]);

        if ($expected < 0) {
            return 'Captcha expirado. Recarregue a página.';
        }

        if (!ctype_digit(trim($given))) {
            return 'Captcha inválido.';
        }

        if ((int) $given !== $expected) {
            return 'Captcha incorreto.';
        }

        return null; // ok
    }
}