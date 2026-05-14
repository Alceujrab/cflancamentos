@extends('layouts.app')
@section('title', 'Relatórios')
@section('content')
@php
  $fmt = fn($v) => 'R$ ' . number_format((float)$v, 2, ',', '.');
  $resumo = $dados['resumo'];
  $lancamentos = $dados['lancamentos'];
  $agrupado = $dados['agrupado'];
@endphp

<header class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
  <div>
    <h1 class="text-2xl font-bold tracking-tight">Relatórios</h1>
    <p class="text-sm text-muted mt-1">Análises financeiras com exportação em PDF profissional.</p>
  </div>
  <a href="{{ route('relatorios.pdf', request()->query()) }}" target="_blank"
     class="inline-flex items-center gap-2 gradient-accent text-white px-5 py-2.5 rounded-xl font-semibold shadow-accent hover:shadow-lg transition-shadow self-start">
    <span class="material-symbols-rounded">picture_as_pdf</span> Gerar PDF
  </a>
</header>

<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
  @foreach($tipos as $key => [$label, $icon])
    <a href="{{ route('relatorios.index', array_merge(request()->except('tipo'), ['tipo' => $key])) }}"
       class="bg-white rounded-2xl p-4 border-2 shadow-card hover:shadow-card-lg transition-all flex items-center gap-3
         {{ $tipo === $key ? 'border-navy-600 bg-navy-50/50' : 'border-line' }}">
      <div class="w-10 h-10 rounded-lg flex items-center justify-center
        {{ $tipo === $key ? 'gradient-brand text-white' : 'bg-bg text-slate-500' }}">
        <span class="material-symbols-rounded">{{ $icon }}</span>
      </div>
      <span class="text-sm font-semibold leading-tight">{{ $label }}</span>
    </a>
  @endforeach
</div>

<form method="GET" class="bg-white rounded-2xl shadow-card border border-line p-4 mb-6 grid grid-cols-2 md:grid-cols-5 gap-3 items-end">
  <input type="hidden" name="tipo" value="{{ $tipo }}">
  <div>
    <label class="block text-[11px] font-bold text-muted uppercase tracking-wider mb-1">De</label>
    <input type="date" name="de" value="{{ $de }}" class="w-full bg-white border border-line rounded-lg px-3 py-2 text-sm outline-none focus:border-navy-500">
  </div>
  <div>
    <label class="block text-[11px] font-bold text-muted uppercase tracking-wider mb-1">Até</label>
    <input type="date" name="ate" value="{{ $ate }}" class="w-full bg-white border border-line rounded-lg px-3 py-2 text-sm outline-none focus:border-navy-500">
  </div>
  <div>
    <label class="block text-[11px] font-bold text-muted uppercase tracking-wider mb-1">Cliente</label>
    <select name="cliente_id" class="w-full bg-white border border-line rounded-lg px-3 py-2 text-sm outline-none focus:border-navy-500">
      <option value="">Todos</option>
      @foreach($clientes as $c)
        <option value="{{ $c->id }}" @selected($cliente_id == $c->id)>{{ $c->nome }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="block text-[11px] font-bold text-muted uppercase tracking-wider mb-1">Veículo</label>
    <select name="veiculo_id" class="w-full bg-white border border-line rounded-lg px-3 py-2 text-sm outline-none focus:border-navy-500">
      <option value="">Todos</option>
      @foreach($veiculos as $v)
        <option value="{{ $v->id }}" @selected($veiculo_id == $v->id)>{{ $v->placa }}</option>
      @endforeach
    </select>
  </div>
  <button class="gradient-brand text-white px-5 py-2 rounded-lg font-semibold text-sm shadow-brand col-span-2 md:col-span-1">Aplicar</button>
</form>

<section class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
  <div class="bg-white rounded-2xl p-5 shadow-card border border-line">
    <p class="text-xs font-semibold tracking-wider text-muted uppercase">Receitas</p>
    <p class="text-xl font-bold text-emerald-600 mt-2">{{ $fmt($resumo['receitas']) }}</p>
  </div>
  <div class="bg-white rounded-2xl p-5 shadow-card border border-line">
    <p class="text-xs font-semibold tracking-wider text-muted uppercase">Despesas</p>
    <p class="text-xl font-bold text-brand-600 mt-2">{{ $fmt($resumo['despesas']) }}</p>
  </div>
  <div class="bg-white rounded-2xl p-5 shadow-card border border-line">
    <p class="text-xs font-semibold tracking-wider text-muted uppercase">Saldo</p>
    <p class="text-xl font-bold mt-2 {{ $resumo['saldo'] >= 0 ? 'text-navy-700' : 'text-brand-600' }}">{{ $fmt($resumo['saldo']) }}</p>
  </div>
  <div class="bg-white rounded-2xl p-5 shadow-card border border-line">
    <p class="text-xs font-semibold tracking-wider text-muted uppercase">Lançamentos</p>
    <p class="text-xl font-bold text-ink mt-2">{{ $resumo['total'] }}</p>
  </div>
</section>

@if($tipo === 'geral')
  <div class="bg-white rounded-2xl shadow-card border border-line overflow-hidden">
    <div class="p-5 border-b border-line"><h2 class="font-bold">{{ $tituloTipo }}</h2></div>
    <table class="w-full">
      <thead class="bg-bg border-b border-line">
        <tr class="text-left text-[11px] font-bold tracking-wider text-muted uppercase">
          <th class="px-6 py-3">Data</th><th class="px-6 py-3">Tipo</th>
          <th class="px-6 py-3">Descrição</th><th class="px-6 py-3">Cliente</th>
          <th class="px-6 py-3">Veículo</th><th class="px-6 py-3 text-right">Valor</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-line">
        @forelse($lancamentos as $l)
          <tr>
            <td class="px-6 py-3 text-sm">{{ $l->data->format('d/m/Y') }}</td>
            <td class="px-6 py-3">
              <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $l->tipo === 'receita' ? 'bg-emerald-50 text-emerald-700' : 'bg-brand-50 text-brand-700' }}">{{ ucfirst($l->tipo) }}</span>
            </td>
            <td class="px-6 py-3 text-sm font-medium">{{ $l->descricao }}</td>
            <td class="px-6 py-3 text-sm text-slate-600">{{ $l->cliente?->nome ?? '—' }}</td>
            <td class="px-6 py-3 text-sm font-mono">{{ $l->veiculo?->placa ?? '—' }}</td>
            <td class="px-6 py-3 text-right text-sm font-bold {{ $l->tipo === 'receita' ? 'text-emerald-600' : 'text-brand-600' }}">
              {{ $l->tipo === 'receita' ? '+' : '−' }}{{ $fmt($l->valor) }}
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-muted">Sem lançamentos no período.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
@elseif($tipo === 'consolidado')
  <div class="bg-white rounded-2xl shadow-card border border-line overflow-hidden">
    <div class="p-5 border-b border-line"><h2 class="font-bold">{{ $tituloTipo }}</h2></div>
    <table class="w-full">
      <thead class="bg-bg border-b border-line">
        <tr class="text-left text-[11px] font-bold tracking-wider text-muted uppercase">
          <th class="px-6 py-3">Mês</th><th class="px-6 py-3 text-right">Receitas</th>
          <th class="px-6 py-3 text-right">Despesas</th><th class="px-6 py-3 text-right">Saldo</th>
          <th class="px-6 py-3 text-right">Lançamentos</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-line">
        @forelse($agrupado as $row)
          @php $saldo = $row['receitas'] - $row['despesas']; @endphp
          <tr>
            <td class="px-6 py-3 text-sm font-semibold">{{ \Carbon\Carbon::parse($row['mes'].'-01')->translatedFormat('F / Y') }}</td>
            <td class="px-6 py-3 text-right text-sm text-emerald-600 font-semibold">{{ $fmt($row['receitas']) }}</td>
            <td class="px-6 py-3 text-right text-sm text-brand-600 font-semibold">{{ $fmt($row['despesas']) }}</td>
            <td class="px-6 py-3 text-right text-sm font-bold {{ $saldo >= 0 ? 'text-navy-700' : 'text-brand-600' }}">{{ $fmt($saldo) }}</td>
            <td class="px-6 py-3 text-right text-sm">{{ $row['total'] }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-muted">Sem dados no período.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
@else
  <div class="space-y-4">
    @forelse($agrupado as $chave => $row)
      @php $saldo = $row['receitas'] - $row['despesas']; @endphp
      <div class="bg-white rounded-2xl shadow-card border border-line overflow-hidden">
        <div class="p-5 border-b border-line flex flex-wrap items-center justify-between gap-3">
          <div>
            <h3 class="font-bold">{{ $chave }}</h3>
            @if(isset($row['modelo']))<p class="text-xs text-muted">{{ $row['modelo'] }}</p>@endif
          </div>
          <div class="flex gap-4 text-xs">
            <span class="text-emerald-600 font-bold">+{{ $fmt($row['receitas']) }}</span>
            <span class="text-brand-600 font-bold">−{{ $fmt($row['despesas']) }}</span>
            <span class="font-bold {{ $saldo >= 0 ? 'text-navy-700' : 'text-brand-600' }}">= {{ $fmt($saldo) }}</span>
          </div>
        </div>
        <table class="w-full">
          <tbody class="divide-y divide-line">
            @foreach($row['itens'] as $l)
              <tr>
                <td class="px-6 py-2.5 text-sm w-24">{{ $l->data->format('d/m/Y') }}</td>
                <td class="px-6 py-2.5 text-sm">{{ $l->descricao }}</td>
                <td class="px-6 py-2.5 text-right text-sm font-semibold {{ $l->tipo === 'receita' ? 'text-emerald-600' : 'text-brand-600' }}">
                  {{ $l->tipo === 'receita' ? '+' : '−' }}{{ $fmt($l->valor) }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @empty
      <div class="bg-white rounded-2xl border border-line p-12 text-center text-sm text-muted">Sem dados no período.</div>
    @endforelse
  </div>
@endif
@endsection
