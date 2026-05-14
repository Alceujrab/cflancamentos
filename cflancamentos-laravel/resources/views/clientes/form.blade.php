@extends('layouts.app')
@section('title', $cliente->exists ? 'Editar Cliente' : 'Novo Cliente')
@section('content')
@php
  $inputClass = 'w-full bg-white border border-line rounded-xl px-4 py-2.5 text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-100 outline-none transition';
  $labelClass = 'block text-xs font-semibold text-slate-700 mb-1.5';
@endphp

<header class="mb-6">
  <a href="{{ route('clientes.index') }}" class="inline-flex items-center gap-1 text-sm text-muted hover:text-navy-700 mb-3">
    <span class="material-symbols-rounded text-[18px]">arrow_back</span> Voltar
  </a>
  <h1 class="text-2xl font-bold tracking-tight">{{ $cliente->exists ? 'Editar Cliente' : 'Novo Cliente' }}</h1>
  <p class="text-sm text-muted mt-1">Preencha os dados cadastrais.</p>
</header>

<form method="POST" action="{{ $cliente->exists ? route('clientes.update', $cliente) : route('clientes.store') }}"
      class="bg-white rounded-2xl shadow-card border border-line p-6 md:p-8 space-y-5 max-w-3xl">
  @csrf
  @if($cliente->exists) @method('PUT') @endif

  <div>
    <label class="{{ $labelClass }}">Nome completo *</label>
    <input name="nome" value="{{ old('nome', $cliente->nome) }}" required class="{{ $inputClass }}" placeholder="Ex: João da Silva">
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
      <label class="{{ $labelClass }}">CPF/CNPJ</label>
      <input name="cpf_cnpj" value="{{ old('cpf_cnpj', $cliente->cpf_cnpj) }}" class="{{ $inputClass }} font-mono" placeholder="000.000.000-00">
    </div>
    <div>
      <label class="{{ $labelClass }}">Telefone</label>
      <input name="telefone" value="{{ old('telefone', $cliente->telefone) }}" class="{{ $inputClass }}" placeholder="(11) 99999-9999">
    </div>
  </div>

  <div>
    <label class="{{ $labelClass }}">E-mail</label>
    <input type="email" name="email" value="{{ old('email', $cliente->email) }}" class="{{ $inputClass }}" placeholder="cliente@email.com">
  </div>

  <label class="inline-flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $cliente->ativo ?? true) ? 'checked' : '' }}
           class="rounded border-line text-navy-600 focus:ring-navy-300">
    Cliente ativo
  </label>

  <div class="flex gap-3 pt-4 border-t border-line">
    <button class="gradient-brand text-white px-6 py-2.5 rounded-xl font-semibold shadow-brand hover:shadow-lg transition-shadow">Salvar</button>
    <a href="{{ route('clientes.index') }}" class="px-6 py-2.5 rounded-xl border border-line bg-white hover:bg-bg text-sm font-semibold">Cancelar</a>
  </div>
</form>
@endsection
