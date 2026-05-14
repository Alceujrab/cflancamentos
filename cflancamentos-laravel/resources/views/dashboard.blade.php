@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@php
  $fmt = fn($v) => 'R$ ' . number_format((float)$v, 2, ',', '.');
  $serieJson = $serie->values()->toJson();
@endphp

<section class="gradient-hero rounded-2xl p-6 md:p-8 mb-8 text-white shadow-brand relative overflow-hidden">
  <div class="absolute -right-10 -top-10 w-64 h-64 rounded-full bg-brand-500/20 blur-3xl"></div>
  <div class="absolute -right-20 -bottom-20 w-72 h-72 rounded-full bg-navy-400/30 blur-3xl"></div>
  <div class="relative flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
      <p class="text-brand-300 text-xs font-semibold tracking-widest uppercase mb-2">Painel Financeiro</p>
      <h1 class="text-3xl md:text-4xl font-bold tracking-tight">Olá, bom trabalho!</h1>
      <p class="text-navy-100/80 mt-2 max-w-lg">Acompanhe o desempenho financeiro da sua operação em tempo real.</p>
    </div>
    <a href="{{ route('lancamentos.create') }}" class="self-start md:self-end inline-flex items-center gap-2 bg-white text-navy-700 px-5 py-2.5 rounded-xl font-semibold shadow-card hover:shadow-card-lg transition-shadow">
      <span class="material-symbols-rounded">add_circle</span> Novo Lançamento
    </a>
  </div>
</section>

<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
  <div class="bg-white rounded-2xl p-5 shadow-card border border-line">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold tracking-wider text-muted uppercase">Receitas (mês)</span>
      <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
        <span class="material-symbols-rounded text-emerald-600 text-[20px]">trending_up</span>
      </div>
    </div>
    <p class="text-2xl font-bold text-emerald-600">{{ $fmt($receitasMes) }}</p>
  </div>
  <div class="bg-white rounded-2xl p-5 shadow-card border border-line">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold tracking-wider text-muted uppercase">Despesas (mês)</span>
      <div class="w-9 h-9 rounded-lg bg-brand-50 flex items-center justify-center">
        <span class="material-symbols-rounded text-brand-600 text-[20px]">trending_down</span>
      </div>
    </div>
    <p class="text-2xl font-bold text-brand-600">{{ $fmt($despesasMes) }}</p>
  </div>
  <div class="bg-white rounded-2xl p-5 shadow-card border border-line">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold tracking-wider text-muted uppercase">Saldo</span>
      <div class="w-9 h-9 rounded-lg bg-navy-50 flex items-center justify-center">
        <span class="material-symbols-rounded text-navy-700 text-[20px]">account_balance_wallet</span>
      </div>
    </div>
    <p class="text-2xl font-bold {{ $saldoMes >= 0 ? 'text-navy-700' : 'text-brand-600' }}">{{ $fmt($saldoMes) }}</p>
  </div>
  <div class="bg-white rounded-2xl p-5 shadow-card border border-line">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold tracking-wider text-muted uppercase">Cadastros</span>
      <div class="w-9 h-9 rounded-lg bg-navy-50 flex items-center justify-center">
        <span class="material-symbols-rounded text-navy-700 text-[20px]">groups</span>
      </div>
    </div>
    <p class="text-2xl font-bold text-ink">{{ $totalClientes }} <span class="text-sm font-medium text-muted">clientes</span></p>
    <p class="text-xs text-muted mt-0.5">{{ $totalVeiculos }} veículos cadastrados</p>
  </div>
</section>

<section class="grid grid-cols-1 lg:grid-cols-3 gap-5">
  <div class="bg-white rounded-2xl p-6 shadow-card border border-line lg:col-span-2">
    <div class="flex items-center justify-between mb-5">
      <div>
        <h2 class="text-base font-bold">Receitas vs. Despesas</h2>
        <p class="text-xs text-muted">Últimos 6 meses</p>
      </div>
      <div class="flex items-center gap-4 text-xs">
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-navy-600"></span>Receitas</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-brand-500"></span>Despesas</span>
      </div>
    </div>
    <canvas id="chart" height="110"></canvas>
  </div>

  <div class="bg-white rounded-2xl p-6 shadow-card border border-line">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-base font-bold">Últimos lançamentos</h2>
      <a href="{{ route('lancamentos.index') }}" class="text-xs font-semibold text-navy-700 hover:text-navy-900">Ver todos →</a>
    </div>
    <ul class="space-y-3">
      @forelse($ultimos as $l)
        <li class="flex items-center gap-3 py-2 border-b border-line last:border-0">
          <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0
            {{ $l->tipo === 'receita' ? 'bg-emerald-50' : 'bg-brand-50' }}">
            <span class="material-symbols-rounded text-[18px] {{ $l->tipo === 'receita' ? 'text-emerald-600' : 'text-brand-600' }}">
              {{ $l->tipo === 'receita' ? 'arrow_upward' : 'arrow_downward' }}
            </span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold truncate">{{ $l->descricao }}</p>
            <p class="text-xs text-muted">{{ $l->data->format('d/m/Y') }} · {{ $l->cliente?->nome ?? 'Sem cliente' }}</p>
          </div>
          <span class="text-sm font-bold {{ $l->tipo === 'receita' ? 'text-emerald-600' : 'text-brand-600' }}">
            {{ $l->tipo === 'receita' ? '+' : '−' }}{{ $fmt($l->valor) }}
          </span>
        </li>
      @empty
        <li class="text-sm text-muted text-center py-6">Nenhum lançamento ainda.</li>
      @endforelse
    </ul>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const serie = {!! $serieJson !!};
new Chart(document.getElementById('chart'), {
  type: 'bar',
  data: {
    labels: serie.map(s => s.label),
    datasets: [
      { label: 'Receitas', data: serie.map(s => s.receita), backgroundColor: '#254eea', borderRadius: 6, barThickness: 18 },
      { label: 'Despesas', data: serie.map(s => s.despesa), backgroundColor: '#f97316', borderRadius: 6, barThickness: 18 },
    ]
  },
  options: {
    responsive: true,
    plugins: { legend: { display:false } },
    scales: {
      x: { ticks: { color: '#64748b', font:{size:11} }, grid: { display:false } },
      y: { ticks: { color: '#64748b', font:{size:11} }, grid: { color:'#f1f5f9' } }
    }
  }
});
</script>
@endsection
