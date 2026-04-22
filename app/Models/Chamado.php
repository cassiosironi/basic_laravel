<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chamado extends Model
{
    protected $table = 'chamados';

    protected $fillable = [
        'titulo',
        'descricao',
        'tipo',
        'anexo',
        'autor_id',
        'status',
        'data_abertura',
        'data_conclusao',
        'orientacoes',
    ];

    public $timestamps = false; // você controla datas manualmente
}
