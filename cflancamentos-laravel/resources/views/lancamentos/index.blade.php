@extends('layouts.app')
@section('title', 'Lançamentos')
@section('content')
@php $fmt = fn($v) => 'R$ ' . number_format((float)$v, 2, ',', '.'); @endphp

<header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
  <div>
    <h1 class="text-2xl font-bold tracking-tight">Lançamentos Financeiros</h1>
    <p class="text-sm text-muted mt-1">Receitas e despesas vinculadas a clientes e veículos.</p>
  </div>
  <a href="{{ route('lancamentos.create') }}" class="inline-flex items-center gap-2 gradient-accent text-white px-5 py-2.5 rounded-xl font-semibold shadow-accent hover:shadow-lg transition-shadow self-start md:self-auto">
    <span class="material-symbols-rounded text-[20px]">add_circle</span> Novo lançamento
  </a>
</header>

<form method="GET" class="bg-white rounded-2xl shadow-card border border-line p-4 mb-6 flex flex-wrap gap-3 items-end">
  <div>
    <label class="block text-[11px] font-bold text-muted uppercase tracking-wider mb-1">Tipo</label>
    <select name="tipo" class="bg-white border border-line rounded-lg px-3 py-2 text-sm outline-none focus:border-navy-500">
      <option value="">Todos</option>
      <option value="receita" @selected($tipo==='receita')>Receita</option>
      <option value="despesa" @selected($tipo==='despesa')>Despesa</option>
    </select>
  </div>
  <div>
    <label class="block text-[11px] font-bold text-muted uppercase tracking-wider mb-1">De</label>
    <input type="date" name="de" value="{{ $de }}" class="bg-white border border-line rounded-lg px-3 py-2 text-sm outline-none focus:border-navy-500">
  </div>
  <div>
    <label class="block text-[11px] font-bold text-muted uppercase tracking-wider mb-1">Até</label>
    <input type="date" name="ate" value="{{ $ate }}" class="bg-white border border-line rounded-lg px-3 py-2 text-sm outline-none focus:border-navy-500">
  </div>
  <button class="gradient-brand text-white px-5 py-2 rounded-lg font-semibold text-sm shadow-brand">Filtrar</button>
  <a href="{{ route('lancamentos.index') }}" class="px-5 py-2 rounded-lg border border-line bg-white hover:bg-bg text-sm font-semibold">Limpar</a>
</form>

<div class="bg-white rounded-2xl shadow-card border border-line overflow-hidden">
  <table class="w-full">
    <thead class="bg-bg border-b border-line">
      <tr class="text-left text-[11px] font-bold tracking-wider text-muted uppercase">
        <th class="px-6 py-3">Data</th>
        <th class="px-6 py-3">Tipo</th>
        <th class="px-6 py-3">Descrição</th>
        <th class="px-6 py-3">Cliente</th>
        <th class="px-6 py-3">Veículo</th>
        <th class="px-6 py-3 text-right">Valor</th>
        <th class="px-6 py-3 text-right">Ações</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-line">
      @forelse($lancamentos as $l)
        <tr class="hover:bg-bg/60 transition-colors">
          <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $l->data->format('d/m/Y') }}</td>
          <td class="px-6 py-4">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold
              {{ $l->tipo === 'receita' ? 'bg-emerald-50 text-emerald-700' : 'bg-brand-50 text-brand-700' }}">
              <span class="material-symbols-rounded text-[14px]">{{ $l->tipo === 'receita' ? 'trending_up' : 'trending_down' }}</span>
              {{ ucfirst($l->tipo) }}
            </span>
          </td>
          <td class="px-6 py-4 text-sm font-semibold">
            {{ $l->descricao }}
            @if($l->anexos_count ?? $l->anexos->count())
              <span class="ml-1 inline-flex items-center gap-0.5 text-[10px] text-navy-700 bg-navy-50 px-1.5 py-0.5 rounded-full font-bold align-middle">
                <span class="material-symbols-rounded text-[12px]">attach_file</span>{{ $l->anexos_count ?? $l->anexos->count() }}
              </span>
            @endif
          </td>
          <td class="px-6 py-4 text-sm text-slate-600">{{ $l->cliente?->nome ?? '—' }}</td>
          <td class="px-6 py-4 text-sm font-mono text-slate-600">{{ $l->veiculo?->placa ?? '—' }}</td>
          <td class="px-6 py-4 text-right text-sm font-bold {{ $l->tipo === 'receita' ? 'text-emerald-600' : 'text-brand-600' }}">
            {{ $l->tipo === 'receita' ? '+' : '−' }}{{ $fmt($l->valor) }}
          </td>
          <td class="px-6 py-4 text-right">
            <div class="inline-flex items-center gap-1">
              <a href="{{ route('lancamentos.edit', $l) }}" class="p-2 rounded-lg hover:bg-navy-50 text-navy-700">
                <span class="material-symbols-rounded text-[20px]">edit</span>
              </a>
              <form method="POST" action="{{ route('lancamentos.destroy', $l) }}" class="inline" onsubmit="return confirm('Remover lançamento?')">
                @csrf @method('DELETE')
                <button class="p-2 rounded-lg hover:bg-brand-50 text-brand-600">
                  <span class="material-symbols-rounded text-[20px]">delete</span>
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="px-6 py-12 text-center text-sm text-muted">Nenhum lançamento encontrado.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-6">{{ $lancamentos->links() }}</div>
@endsection
