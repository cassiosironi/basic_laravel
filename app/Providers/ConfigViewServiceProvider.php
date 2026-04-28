<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ConfigViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {

            // evita repetir query na mesma request
            static $config = null;

            if ($config === null) {
                $rows = DB::select("SELECT * FROM configuracoes LIMIT 1");
                $config = isset($rows[0]) ? $rows[0] : null;
            }

            $view->with('config', $config);
        });
    }
}