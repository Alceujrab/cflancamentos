<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LancamentoAnexo extends Model
{
    protected $fillable = ['lancamento_financeiro_id', 'nome_original', 'path', 'mime', 'tamanho'];

    public function lancamento(): BelongsTo
    {
        return $this->belongsTo(LancamentoFinanceiro::class, 'lancamento_financeiro_id');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime, 'image/');
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->mime === 'application/pdf';
    }
}
