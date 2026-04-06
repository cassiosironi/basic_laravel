
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    // Não estamos usando Eloquent para persistência,
    // mas deixamos aqui por organização.
    protected $table = 'banners';

    protected $fillable = [
        'image', 'title', 'subtitle', 'active'
    ];
}
