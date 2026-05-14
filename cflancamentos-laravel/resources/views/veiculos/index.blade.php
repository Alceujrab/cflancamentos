@extends('layouts.app')
@section('title', 'Veículos')
@section('content')
<header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
  <div>
    <h1 class="text-2xl font-bold tracking-tight">Veículos</h1>
    <p class="text-sm text-muted mt-1">Gerencie a frota cadastrada.</p>
  </div>
  <div class="flex flex-wrap gap-3">
    <form method="GET" class="flex items-center bg-white border border-line rounded-xl px-3 py-2 shadow-card">
      <span class="material-symbols-rounded text-muted text-[20px] mr-2">search</span>
      <input name="q" value="{{ $q }}" placeholder="Placa ou modelo..." class="bg-transparent border-0 focus:ring-0 text-sm w-56 p-0">
    </form>
    <a href="{{ route('veiculos.create') }}" class="inline-flex items-center gap-2 gradient-accent text-white px-5 py-2.5 rounded-xl font-semibold shadow-accent hover:shadow-lg transition-shadow">
      <span class="material-symbols-rounded text-[20px]">add</span> Novo veículo
    </a>
  </div>
</header>

<div class="bg-white rounded-2xl shadow-card border border-line overflow-hidden">
  <table class="w-full">
    <thead class="bg-bg border-b border-line">
      <tr class="text-left text-[11px] font-bold tracking-wider text-muted uppercase">
        <th class="px-6 py-3">Placa</th>
        <th class="px-6 py-3">Modelo</th>
        <th class="px-6 py-3">Observação</th>
        <th class="px-6 py-3">Status</th>
        <th class="px-6 py-3 text-right">Ações</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-line">
      @forelse($veiculos as $v)
        <tr class="hover:bg-bg/60 transition-colors">
          <td class="px-6 py-4">
            <span class="font-mono font-bold bg-navy-50 text-navy-700 px-2.5 py-1 rounded-md text-sm">{{ $v->placa }}</span>
          </td>
          <td class="px-6 py-4 text-sm font-medium">{{ $v->modelo }}</td>
          <td class="px-6 py-4 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($v->observacao, 60) ?: '—' }}</td>
          <td class="px-6 py-4">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold
              {{ $v->ativo ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
              <span class="w-1.5 h-1.5 rounded-full {{ $v->ativo ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
              {{ $v->ativo ? 'Ativo' : 'Inativo' }}
            </span>
          </td>
          <td class="px-6 py-4 text-right">
            <div class="inline-flex items-center gap-1">
              <a href="{{ route('veiculos.edit', $v) }}" class="p-2 rounded-lg hover:bg-navy-50 text-navy-700">
                <span class="material-symbols-rounded text-[20px]">edit</span>
              </a>
              <form method="POST" action="{{ route('veiculos.destroy', $v) }}" class="inline" onsubmit="return confirm('Remover veículo?')">
                @csrf @method('DELETE')
                <button class="p-2 rounded-lg hover:bg-brand-50 text-brand-600">
                  <span class="material-symbols-rounded text-[20px]">delete</span>
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-muted">Nenhum veículo cadastrado.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-6">{{ $veiculos->links() }}</div>
@endsection
