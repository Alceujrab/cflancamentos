@extends('layouts.app')
@section('title', 'Configurações')
@section('content')
@php
  $inputClass = 'w-full bg-white border border-line rounded-xl px-4 py-2.5 text-sm focus:border-navy-500 focus:ring-2 focus:ring-navy-100 outline-none transition';
  $labelClass = 'block text-xs font-semibold text-slate-700 mb-1.5';
@endphp

<header class="mb-6">
  <h1 class="text-2xl font-bold tracking-tight">Configurações Empresariais</h1>
  <p class="text-sm text-muted mt-1">Dados da empresa exibidos em relatórios e documentos.</p>
</header>

<form method="POST" action="{{ route('configuracoes.update') }}"
      class="bg-white rounded-2xl shadow-card border border-line p-6 md:p-8 space-y-5 max-w-3xl">
  @csrf @method('PUT')

  <div>
    <label class="{{ $labelClass }}">Razão Social</label>
    <input name="razao_social" value="{{ old('razao_social', $config->razao_social) }}" class="{{ $inputClass }}" placeholder="Nome da empresa">
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
      <label class="{{ $labelClass }}">CNPJ</label>
      <input name="cnpj" value="{{ old('cnpj', $config->cnpj) }}" class="{{ $inputClass }} font-mono" placeholder="00.000.000/0001-00">
    </div>
    <div>
      <label class="{{ $labelClass }}">E-mail de Contato</label>
      <input type="email" name="email" value="{{ old('email', $config->email) }}" class="{{ $inputClass }}" placeholder="contato@empresa.com.br">
    </div>
    <div>
      <label class="{{ $labelClass }}">Telefone Comercial</label>
      <input type="tel" name="telefone" value="{{ old('telefone', $config->telefone) }}" class="{{ $inputClass }}" placeholder="(11) 99999-9999">
    </div>
    <div>
      <label class="{{ $labelClass }}">Website</label>
      <input type="url" name="website" value="{{ old('website', $config->website) }}" class="{{ $inputClass }}" placeholder="https://www.empresa.com.br">
    </div>
  </div>

  <div>
    <label class="{{ $labelClass }}">Endereço Completo</label>
    <textarea name="endereco" rows="3" class="{{ $inputClass }} resize-none" placeholder="Rua, número, bairro, cidade - UF">{{ old('endereco', $config->endereco) }}</textarea>
  </div>

  <div class="flex gap-3 pt-4 border-t border-line">
    <button class="gradient-brand text-white px-6 py-2.5 rounded-xl font-semibold shadow-brand hover:shadow-lg transition-shadow">Salvar Configurações</button>
  </div>
</form>
@endsection
