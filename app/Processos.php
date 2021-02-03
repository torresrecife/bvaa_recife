<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Processos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CodigoProcesso', 'Carteira', 'StatusProcesso', 'FaseProcesso', 'Area',
    ];
}
