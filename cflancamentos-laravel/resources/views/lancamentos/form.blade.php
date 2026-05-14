@extends('layouts.app')
@section('title', $lancamento->exists ? 'Editar Lançamento' : 'Novo Lançamento')
@section('content')
@php
  $inputClass = 'w-full bg-white border border-line rounded-xl px-4 py-2.5 text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-100 outline-none transition';
  $labelClass = 'block text-xs font-semibold text-slate-700 mb-1.5';
  $tipoAtual = old('tipo', $lancamento->tipo);
@endphp

<header class="mb-6">
  <a href="{{ route('lancamentos.index') }}" class="inline-flex items-center gap-1 text-sm text-muted hover:text-navy-700 mb-3">
    <span class="material-symbols-rounded text-[18px]">arrow_back</span> Voltar
  </a>
  <h1 class="text-2xl font-bold tracking-tight">{{ $lancamento->exists ? 'Editar Lançamento' : 'Novo Lançamento' }}</h1>
  <p class="text-sm text-muted mt-1">Registre uma transação financeira.</p>
</header>

<form method="POST" enctype="multipart/form-data" action="{{ $lancamento->exists ? route('lancamentos.update', $lancamento) : route('lancamentos.store') }}"
      class="bg-white rounded-2xl shadow-card border border-line p-6 md:p-8 space-y-6 max-w-4xl">
  @csrf
  @if($lancamento->exists) @method('PUT') @endif

  <div>
    <label class="{{ $labelClass }}">Tipo de Operação *</label>
    <div class="grid grid-cols-2 gap-3 mt-1">
      <label class="cursor-pointer">
        <input type="radio" name="tipo" value="receita" class="peer hidden" {{ $tipoAtual === 'receita' ? 'checked' : '' }}>
        <div class="text-center py-4 rounded-xl border-2 border-line bg-white peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 transition-all flex items-center justify-center gap-2 font-semibold">
          <span class="material-symbols-rounded">trending_up</span> Receita
        </div>
      </label>
      <label class="cursor-pointer">
        <input type="radio" name="tipo" value="despesa" class="peer hidden" {{ $tipoAtual === 'despesa' ? 'checked' : '' }}>
        <div class="text-center py-4 rounded-xl border-2 border-line bg-white peer-checked:border-brand-500 peer-checked:bg-brand-50 peer-checked:text-brand-700 transition-all flex items-center justify-center gap-2 font-semibold">
          <span class="material-symbols-rounded">trending_down</span> Despesa
        </div>
      </label>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
      <label class="{{ $labelClass }}">Cliente</label>
      <select name="cliente_id" class="{{ $inputClass }}">
        <option value="">— Selecione —</option>
        @foreach($clientes as $c)
          <option value="{{ $c->id }}" @selected(old('cliente_id', $lancamento->cliente_id) == $c->id)>{{ $c->nome }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="{{ $labelClass }}">Veículo (opcional)</label>
      <select name="veiculo_id" class="{{ $inputClass }}">
        <option value="">— Nenhum —</option>
        @foreach($veiculos as $v)
          <option value="{{ $v->id }}" @selected(old('veiculo_id', $lancamento->veiculo_id) == $v->id)>{{ $v->placa }} · {{ $v->modelo }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
      <label class="{{ $labelClass }}">Data do Lançamento *</label>
      <input type="date" name="data" required
             value="{{ old('data', optional($lancamento->data)->format('Y-m-d') ?? $lancamento->data) }}" class="{{ $inputClass }}">
    </div>
    <div>
      <label class="{{ $labelClass }}">Valor (R$) *</label>
      <input name="valor" required value="{{ old('valor', $lancamento->valor) }}" placeholder="0,00"
             class="{{ $inputClass }} text-xl font-bold text-navy-700">
    </div>
  </div>

  <div>
    <label class="{{ $labelClass }}">Descrição *</label>
    <input name="descricao" required value="{{ old('descricao', $lancamento->descricao) }}" class="{{ $inputClass }}" placeholder="Ex: Pagamento de frete">
  </div>

  <div>
    <label class="{{ $labelClass }}">Observação</label>
    <textarea name="observacao" rows="3" class="{{ $inputClass }} resize-none" placeholder="Informações adicionais...">{{ old('observacao', $lancamento->observacao) }}</textarea>
  </div>

  <div>
    <label class="{{ $labelClass }}">Anexos (PDF ou imagens · máx. 10MB cada)</label>
    <label class="flex flex-col items-center justify-center w-full border-2 border-dashed border-line rounded-xl py-8 px-6 cursor-pointer hover:border-navy-500 hover:bg-navy-50/30 transition-colors">
      <span class="material-symbols-rounded text-navy-600 text-[36px]">cloud_upload</span>
      <span class="text-sm font-semibold mt-2">Clique para enviar arquivos</span>
      <span class="text-xs text-muted mt-1">PDF, JPG, PNG ou WEBP</span>
      <input type="file" name="anexos[]" multiple accept=".pdf,image/*" class="hidden" onchange="document.getElementById('anexoList').textContent = Array.from(this.files).map(f => f.name).join(', ') || 'Nenhum arquivo selecionado'">
    </label>
    <p id="anexoList" class="text-xs text-muted mt-2"></p>
  </div>

  @if($lancamento->exists && $lancamento->anexos->isNotEmpty())
    <div>
      <label class="{{ $labelClass }}">Anexos existentes</label>
      <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        @foreach($lancamento->anexos as $a)
          <div class="border border-line rounded-xl p-3 bg-bg flex items-center gap-3">
            @if($a->is_image)
              <a href="{{ $a->url }}" target="_blank" class="flex-shrink-0">
                <img src="{{ $a->url }}" class="w-14 h-14 rounded-lg object-cover border border-line">
              </a>
            @else
              <a href="{{ $a->url }}" target="_blank" class="w-14 h-14 rounded-lg bg-brand-50 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-rounded text-brand-600">picture_as_pdf</span>
              </a>
            @endif
            <div class="flex-1 min-w-0">
              <a href="{{ $a->url }}" target="_blank" class="text-xs font-semibold truncate block hover:text-navy-700">{{ $a->nome_original }}</a>
              <p class="text-[10px] text-muted">{{ number_format($a->tamanho / 1024, 0, ',', '.') }} KB</p>
            </div>
            <form method="POST" action="{{ route('anexos.destroy', $a) }}" onsubmit="return confirm('Remover anexo?')">
              @csrf @method('DELETE')
              <button type="submit" class="p-1.5 rounded-md text-brand-600 hover:bg-brand-50">
                <span class="material-symbols-rounded text-[18px]">delete</span>
              </button>
            </form>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  <div class="flex gap-3 pt-4 border-t border-line">
    <button class="gradient-brand text-white px-6 py-2.5 rounded-xl font-semibold shadow-brand hover:shadow-lg transition-shadow">Salvar</button>
    <a href="{{ route('lancamentos.index') }}" class="px-6 py-2.5 rounded-xl border border-line bg-white hover:bg-bg text-sm font-semibold">Cancelar</a>
  </div>
</form>
@endsection
