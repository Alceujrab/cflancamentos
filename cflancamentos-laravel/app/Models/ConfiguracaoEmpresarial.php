<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracaoEmpresarial extends Model
{
    use HasFactory;

    protected $fillable = ['razao_social', 'cnpj', 'email', 'telefone', 'website', 'endereco'];

    public static function singleton(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
