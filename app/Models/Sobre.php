<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SobreHome extends Model
{
    protected $table = 'sobre';

    protected $fillable = [
        'image', 'title', 'text', 'active'
    ];
}
