<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'CFLançamentos') · CFLançamentos</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
<script>
tailwind.config = {
  theme: { extend: {
    colors: {
      navy: {
        50:'#eef4ff',100:'#dbe6fe',200:'#bfd2fe',300:'#93b4fd',
        400:'#608ffa',500:'#3b6cf6',600:'#254eea',700:'#1e3dd6',
        800:'#1f34ad',900:'#0f1f5c',950:'#0a163f'
      },
      brand: {
        50:'#fff7ed',100:'#ffedd5',200:'#fed7aa',300:'#fdba74',
        400:'#fb923c',500:'#f97316',600:'#ea580c',700:'#c2410c',
        800:'#9a3412',900:'#7c2d12'
      },
      ink:'#0f172a', muted:'#64748b', line:'#e2e8f0', bg:'#f6f8fc'
    },
    fontFamily:{ sans:['Inter','sans-serif'], mono:['JetBrains Mono','monospace'] },
    boxShadow:{
      'card':'0 1px 2px rgba(15,23,42,.04), 0 1px 3px rgba(15,23,42,.06)',
      'card-lg':'0 4px 12px rgba(15,23,42,.06), 0 12px 28px rgba(15,23,42,.08)',
      'brand':'0 10px 25px -10px rgba(37,78,234,.55)',
      'accent':'0 10px 25px -10px rgba(249,115,22,.55)'
    }
  }}
}
</script>
<style>
body{font-family:'Inter',sans-serif;background:#f6f8fc;color:#0f172a}
.material-symbols-rounded{font-variation-settings:'FILL' 0,'wght' 500,'GRAD' 0,'opsz' 24}
.gradient-brand{background:linear-gradient(135deg,#1e3dd6 0%,#3b6cf6 100%)}
.gradient-accent{background:linear-gradient(135deg,#f97316 0%,#fb923c 100%)}
.gradient-hero{background:linear-gradient(135deg,#0f1f5c 0%,#1e3dd6 60%,#3b6cf6 100%)}
::-webkit-scrollbar{width:8px;height:8px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:10px}
::-webkit-scrollbar-thumb:hover{background:#94a3b8}
.nav-link{position:relative}
.nav-link.active::before{content:'';position:absolute;left:0;top:8px;bottom:8px;width:3px;border-radius:0 4px 4px 0;background:linear-gradient(180deg,#f97316,#fb923c)}
</style>
</head>
<body class="text-ink antialiased">

<header class="fixed top-0 inset-x-0 z-50 h-16 bg-white/85 backdrop-blur-lg border-b border-line">
  <div class="h-full px-6 md:pl-72 md:pr-8 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <button class="md:hidden p-2 rounded-lg hover:bg-bg" onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
        <span class="material-symbols-rounded">menu</span>
      </button>
      <div class="hidden md:flex items-center gap-2 text-sm text-muted">
        <span class="material-symbols-rounded text-base">home</span>
        <span>/</span>
        <span class="text-ink font-semibold">@yield('title','Dashboard')</span>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <button class="p-2 rounded-lg hover:bg-bg relative">
        <span class="material-symbols-rounded text-navy-700">notifications</span>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-brand-500"></span>
      </button>
      <div class="flex items-center gap-3 pl-3 ml-1 border-l border-line">
        <div class="w-9 h-9 rounded-full gradient-brand flex items-center justify-center text-white font-bold text-sm">CF</div>
        <div class="hidden sm:block">
          <p class="text-sm font-semibold leading-tight">Administrador</p>
          <p class="text-xs text-muted leading-tight">admin@cflancamentos</p>
        </div>
      </div>
    </div>
  </div>
</header>

<aside id="sidebar" class="fixed left-0 top-0 h-full w-64 z-40 bg-white border-r border-line flex flex-col -translate-x-full md:translate-x-0 transition-transform">
  <div class="h-16 flex items-center gap-3 px-6 border-b border-line">
    <div class="w-9 h-9 rounded-xl gradient-brand flex items-center justify-center shadow-brand">
      <span class="material-symbols-rounded text-white">paid</span>
    </div>
    <div>
      <h1 class="font-bold text-base leading-tight tracking-tight">CFLançamentos</h1>
      <p class="text-[11px] text-muted leading-tight font-medium">Financial Control</p>
    </div>
  </div>

  @php
    $cur = request()->route()?->getName() ?? '';
    $items = [
      ['dashboard',      'dashboard',      'Dashboard',     route('dashboard')],
      ['group',          'clientes.',      'Clientes',      route('clientes.index')],
      ['directions_car', 'veiculos.',      'Veículos',      route('veiculos.index')],
      ['payments',       'lancamentos.',   'Lançamentos',   route('lancamentos.index')],
      ['summarize',      'relatorios.',    'Relatórios',    route('relatorios.index')],
      ['settings',       'configuracoes.', 'Configurações', route('configuracoes.edit')],
    ];
  @endphp

  <nav class="flex-1 px-3 py-4 space-y-1">
    <p class="px-3 pb-2 text-[10px] font-bold tracking-widest text-muted uppercase">Menu Principal</p>
    @foreach($items as [$icon, $match, $label, $href])
      @php $active = str_starts_with($cur, $match) || $cur === $match; @endphp
      <a href="{{ $href }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all
        {{ $active ? 'active bg-navy-50 text-navy-700' : 'text-slate-600 hover:bg-bg hover:text-ink' }}">
        <span class="material-symbols-rounded text-[22px] {{ $active ? 'text-navy-700' : 'text-slate-400' }}">{{ $icon }}</span>
        {{ $label }}
      </a>
    @endforeach
  </nav>

  <div class="p-4 border-t border-line">
    <a href="{{ route('lancamentos.create') }}" class="flex items-center justify-center gap-2 w-full py-2.5 gradient-accent text-white rounded-xl font-semibold text-sm shadow-accent hover:shadow-lg transition-shadow">
      <span class="material-symbols-rounded text-[20px]">add</span> Novo Lançamento
    </a>
  </div>
</aside>

<main class="pt-20 pb-16 md:pl-72 px-4 md:pr-8">
  <div class="max-w-7xl mx-auto">
    @if(session('ok'))
      <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-emerald-800">
        <span class="material-symbols-rounded text-emerald-600">check_circle</span>
        <span class="font-medium text-sm">{{ session('ok') }}</span>
      </div>
    @endif
    @if($errors->any())
      <div class="mb-6 bg-brand-50 border border-brand-200 rounded-xl px-4 py-3 text-brand-900">
        <div class="font-semibold text-sm flex items-center gap-2 mb-1">
          <span class="material-symbols-rounded text-brand-600">error</span> Corrija os erros abaixo:
        </div>
        <ul class="list-disc list-inside text-sm text-brand-800/90">
          @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
      </div>
    @endif
    @yield('content')
  </div>
</main>

</body>
</html>
