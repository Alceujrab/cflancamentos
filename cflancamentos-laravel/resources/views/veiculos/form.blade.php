@extends('layouts.app')
@section('title', $veiculo->exists ? 'Editar Veículo' : 'Novo Veículo')
@section('content')
@php
  $inputClass = 'w-full bg-white border border-line rounded-xl px-4 py-2.5 text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-100 outline-none transition';
  $labelClass = 'block text-xs font-semibold text-slate-700 mb-1.5';
@endphp

<header class="mb-6">
  <a href="{{ route('veiculos.index') }}" class="inline-flex items-center gap-1 text-sm text-muted hover:text-navy-700 mb-3">
    <span class="material-symbols-rounded text-[18px]">arrow_back</span> Voltar
  </a>
  <h1 class="text-2xl font-bold tracking-tight">{{ $veiculo->exists ? 'Editar Veículo' : 'Novo Veículo' }}</h1>
  <p class="text-sm text-muted mt-1">Dados da unidade da frota.</p>
</header>

<form method="POST" action="{{ $veiculo->exists ? route('veiculos.update', $veiculo) : route('veiculos.store') }}"
      class="bg-white rounded-2xl shadow-card border border-line p-6 md:p-8 space-y-5 max-w-3xl">
  @csrf
  @if($veiculo->exists) @method('PUT') @endif

  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
      <label class="{{ $labelClass }}">Placa *</label>
      <input name="placa" value="{{ old('placa', $veiculo->placa) }}" required
             class="{{ $inputClass }} font-mono uppercase tracking-wider" placeholder="ABC-1234">
    </div>
    <div>
      <label class="{{ $labelClass }}">Modelo *</label>
      <input name="modelo" value="{{ old('modelo', $veiculo->modelo) }}" required class="{{ $inputClass }}" placeholder="Ex: Toyota Corolla 2024">
    </div>
  </div>

  <div>
    <label class="{{ $labelClass }}">Observação</label>
    <textarea name="observacao" rows="4" class="{{ $inputClass }} resize-none" placeholder="Detalhes adicionais...">{{ old('observacao', $veiculo->observacao) }}</textarea>
  </div>

  <label class="inline-flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $veiculo->ativo ?? true) ? 'checked' : '' }}
           class="rounded border-line text-navy-600 focus:ring-navy-300">
    Veículo ativo
  </label>

  <div class="flex gap-3 pt-4 border-t border-line">
    <button class="gradient-brand text-white px-6 py-2.5 rounded-xl font-semibold shadow-brand hover:shadow-lg transition-shadow">Salvar</button>
    <a href="{{ route('veiculos.index') }}" class="px-6 py-2.5 rounded-xl border border-line bg-white hover:bg-bg text-sm font-semibold">Cancelar</a>
  </div>
</form>
@endsection
