@extends('layouts.app')
@section('title', 'Clientes')
@section('content')
<header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
  <div>
    <h1 class="text-2xl font-bold tracking-tight">Clientes</h1>
    <p class="text-sm text-muted mt-1">Gerencie o cadastro de clientes e parceiros.</p>
  </div>
  <div class="flex flex-wrap gap-3">
    <form method="GET" class="flex items-center bg-white border border-line rounded-xl px-3 py-2 shadow-card">
      <span class="material-symbols-rounded text-muted text-[20px] mr-2">search</span>
      <input name="q" value="{{ $q }}" placeholder="Buscar cliente..." class="bg-transparent border-0 focus:ring-0 text-sm w-56 p-0">
    </form>
    <a href="{{ route('clientes.create') }}" class="inline-flex items-center gap-2 gradient-accent text-white px-5 py-2.5 rounded-xl font-semibold shadow-accent hover:shadow-lg transition-shadow">
      <span class="material-symbols-rounded text-[20px]">person_add</span> Novo cliente
    </a>
  </div>
</header>

<div class="bg-white rounded-2xl shadow-card border border-line overflow-hidden">
  <table class="w-full">
    <thead class="bg-bg border-b border-line">
      <tr class="text-left text-[11px] font-bold tracking-wider text-muted uppercase">
        <th class="px-6 py-3">Nome</th>
        <th class="px-6 py-3">CPF/CNPJ</th>
        <th class="px-6 py-3">Telefone</th>
        <th class="px-6 py-3">E-mail</th>
        <th class="px-6 py-3">Status</th>
        <th class="px-6 py-3 text-right">Ações</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-line">
      @forelse($clientes as $c)
        <tr class="hover:bg-bg/60 transition-colors">
          <td class="px-6 py-4">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full gradient-brand text-white flex items-center justify-center text-xs font-bold">
                {{ strtoupper(mb_substr($c->nome, 0, 2)) }}
              </div>
              <span class="font-semibold text-sm">{{ $c->nome }}</span>
            </div>
          </td>
          <td class="px-6 py-4 text-sm text-slate-600">{{ $c->cpf_cnpj }}</td>
          <td class="px-6 py-4 text-sm text-slate-600">{{ $c->telefone }}</td>
          <td class="px-6 py-4 text-sm text-slate-600">{{ $c->email }}</td>
          <td class="px-6 py-4">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold
              {{ $c->ativo ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
              <span class="w-1.5 h-1.5 rounded-full {{ $c->ativo ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
              {{ $c->ativo ? 'Ativo' : 'Inativo' }}
            </span>
          </td>
          <td class="px-6 py-4 text-right">
            <div class="inline-flex items-center gap-1">
              <a href="{{ route('clientes.edit', $c) }}" class="p-2 rounded-lg hover:bg-navy-50 text-navy-700">
                <span class="material-symbols-rounded text-[20px]">edit</span>
              </a>
              <form method="POST" action="{{ route('clientes.destroy', $c) }}" class="inline" onsubmit="return confirm('Remover cliente?')">
                @csrf @method('DELETE')
                <button class="p-2 rounded-lg hover:bg-brand-50 text-brand-600">
                  <span class="material-symbols-rounded text-[20px]">delete</span>
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-muted">Nenhum cliente cadastrado.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-6">{{ $clientes->links() }}</div>
@endsection
