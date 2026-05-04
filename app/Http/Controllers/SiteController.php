<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Support\Notifies;


class SiteController extends Controller
{

    use Notifies;

    // =========================
    // SITE
    // =========================

    public function siteHome(Request $request)
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

        
        // ============================
        // Consulta CEP (ViaCEP)
        // ============================
        $cepInput = trim((string) $request->query('cep', ''));
        $cepResult = null;
        $cepError = null;

        if ($cepInput !== '') {

            // remove caracteres não numéricos (ex: 90.000-000 -> 90000000)
            $cepDigits = preg_replace('/\D+/', '', $cepInput);

            // ViaCEP requer 8 dígitos [1](https://viacep.com.br/)
            if (!preg_match('/^\d{8}$/', $cepDigits)) {
                $cepError = 'CEP inválido. Informe 8 dígitos.';
            } else {
                try {
                    // Chamada HTTP via Laravel Http client [2](https://laravel.com/docs/12.x/http-client)
                    $response = Http::timeout(5)
                        ->get("https://viacep.com.br/ws/{$cepDigits}/json/");

                    // CEP inválido pode retornar 400 [1](https://viacep.com.br/)
                    if ($response->status() === 400) {
                        $cepError = 'CEP inválido (formato incorreto).';
                    } elseif (!$response->ok()) {
                        $cepError = 'Falha ao consultar o serviço de CEP. Tente novamente.';
                    } else {
                        $data = $response->json();

                        // CEP não encontrado retorna {"erro": true} [1](https://viacep.com.br/)
                        if (isset($data['erro']) && $data['erro'] === true) {
                            $cepError = 'CEP não encontrado.';
                        } else {
                            $cepResult = $data;
                        }
                    }
                } catch (\Throwable $e) {
                    $cepError = 'Erro ao consultar o serviço de CEP. Tente novamente.';
                }
            }
        }

        return view('site.home', [
            'banners' => $banners,
            'sobre'   => $sobre,

            // CEP
            'cepInput'  => $cepInput,
            'cepResult' => $cepResult,
            'cepError'  => $cepError,
        ]);
    }

}