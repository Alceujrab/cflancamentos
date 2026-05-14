<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LancamentoFinanceiro extends Model
{
    use HasFactory;

    protected $fillable = ['tipo', 'cliente_id', 'veiculo_id', 'data', 'valor', 'descricao', 'observacao'];

    protected $casts = [
        'data' => 'date',
        'valor' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function veiculo(): BelongsTo
    {
        return $this->belongsTo(Veiculo::class);
    }

    public function anexos(): HasMany
    {
        return $this->hasMany(LancamentoAnexo::class);
    }

    public function scopeReceitas($q) { return $q->where('tipo', 'receita'); }
    public function scopeDespesas($q) { return $q->where('tipo', 'despesa'); }
}
