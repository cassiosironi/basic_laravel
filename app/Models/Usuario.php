<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
        'nome', 'login', 'nível', 'senha', 'ativo'
    ];

    public $timestamps = false; // se você não está usando created_at/updated_at
}


// ## usuarios (senha em MD5)
// CREATE TABLE usuarios (
//   id INT AUTO_INCREMENT PRIMARY KEY,
//   nome VARCHAR(120) NOT NULL,
//   login VARCHAR(80) NOT NULL UNIQUE,
//   senha CHAR(32) NOT NULL,
//   ativo TINYINT(1) NOT NULL DEFAULT 1,
//   created_at DATETIME NULL,
//   updated_at DATETIME NULL
// );

// ## Tabela de logs de login (via Middleware)
// CREATE TABLE admin_login_logs (
//   id INT AUTO_INCREMENT PRIMARY KEY,
//   usuario_id INT NOT NULL,
//   ip VARCHAR(45) NOT NULL,
//   user_agent VARCHAR(255) NULL,
//   created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
// );
