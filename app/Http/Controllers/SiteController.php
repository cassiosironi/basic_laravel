<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Notifies;


class SiteController extends Controller
{

    use Notifies;

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
}