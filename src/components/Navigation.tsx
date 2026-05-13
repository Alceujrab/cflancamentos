"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";

export default function Navigation() {
  const pathname = usePathname();

  return (
    <>
      <header className="fixed top-0 w-full z-50 flex justify-between items-center px-container-padding h-16 bg-surface/30 backdrop-blur-xl border-b border-white/10 shadow-2xl">
        <div className="font-headline-lg text-headline-lg font-bold text-on-surface tracking-tight">CFLançamentos</div>
        <div className="flex items-center gap-gutter">
          <div className="hidden md:flex items-center glass-card px-4 py-1.5 rounded-full border-white/5">
            <span className="material-symbols-outlined text-on-surface-variant text-sm mr-2">search</span>
            <input className="bg-transparent border-none focus:ring-0 text-label-md w-64 p-0 placeholder:text-on-surface-variant/50" placeholder="Buscar lançamentos..." type="text"/>
          </div>
          <div className="flex items-center gap-4">
            <button className="material-symbols-outlined p-2 rounded-full hover:bg-white/5 transition-colors cursor-pointer active:scale-95 duration-200">notifications</button>
            <div className="flex items-center gap-3 glass-card pl-3 pr-1 py-1 rounded-full cursor-pointer active:scale-95 duration-200">
              <span className="text-label-md hidden sm:block">Admin</span>
              <span className="material-symbols-outlined text-primary" style={{ fontVariationSettings: "'FILL' 1" }}>account_circle</span>
            </div>
          </div>
        </div>
      </header>

      <aside className="fixed left-0 top-0 h-full flex flex-col p-glass-margin z-40 h-screen w-64 hidden md:flex border-r border-white/10 bg-surface-container/20 backdrop-blur-2xl shadow-[20px_0_40px_rgba(0,0,0,0.4)]">
        <div className="pt-20 pb-8 px-4">
          <div className="flex flex-col gap-1">
            <span className="font-headline-lg text-primary">CFLançamentos</span>
            <span className="font-label-md text-label-md opacity-60">Financial Control</span>
          </div>
        </div>
        <nav className="flex-1 flex flex-col gap-2">
          <Link href="/" className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-label-md text-label-md active:translate-x-1 ${pathname === '/' ? 'bg-secondary/10 text-secondary border-r-4 border-secondary shadow-[0_0_20px_rgba(78,222,163,0.2)]' : 'text-on-surface-variant hover:bg-white/5 hover:text-on-surface'}`}>
            <span className="material-symbols-outlined">dashboard</span> Dashboard
          </Link>
          <Link href="/clientes" className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-label-md text-label-md active:translate-x-1 ${pathname.startsWith('/clientes') ? 'bg-secondary/10 text-secondary border-r-4 border-secondary shadow-[0_0_20px_rgba(78,222,163,0.2)]' : 'text-on-surface-variant hover:bg-white/5 hover:text-on-surface'}`}>
            <span className="material-symbols-outlined">group</span> Clientes
          </Link>
          <Link href="/veiculos" className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-label-md text-label-md active:translate-x-1 ${pathname.startsWith('/veiculos') ? 'bg-secondary/10 text-secondary border-r-4 border-secondary shadow-[0_0_20px_rgba(78,222,163,0.2)]' : 'text-on-surface-variant hover:bg-white/5 hover:text-on-surface'}`}>
            <span className="material-symbols-outlined">directions_car</span> Veículos
          </Link>
          <Link href="/lancamentos" className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-label-md text-label-md active:translate-x-1 ${pathname.startsWith('/lancamentos') ? 'bg-secondary/10 text-secondary border-r-4 border-secondary shadow-[0_0_20px_rgba(78,222,163,0.2)]' : 'text-on-surface-variant hover:bg-white/5 hover:text-on-surface'}`}>
            <span className="material-symbols-outlined">payments</span> Lançamentos
          </Link>
          <Link href="/relatorios" className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-label-md text-label-md active:translate-x-1 ${pathname.startsWith('/relatorios') ? 'bg-secondary/10 text-secondary border-r-4 border-secondary shadow-[0_0_20px_rgba(78,222,163,0.2)]' : 'text-on-surface-variant hover:bg-white/5 hover:text-on-surface'}`}>
            <span className="material-symbols-outlined">description</span> Relatórios
          </Link>
          <Link href="/configuracoes" className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-label-md text-label-md active:translate-x-1 ${pathname.startsWith('/configuracoes') ? 'bg-secondary/10 text-secondary border-r-4 border-secondary shadow-[0_0_20px_rgba(78,222,163,0.2)]' : 'text-on-surface-variant hover:bg-white/5 hover:text-on-surface'}`}>
            <span className="material-symbols-outlined">settings</span> Configurações
          </Link>
        </nav>
        <div className="mt-auto p-4">
          <Link href="/lancamentos" className="w-full flex items-center justify-center gap-2 bg-secondary text-on-secondary font-bold py-3 rounded-xl glow-emerald active:scale-95 transition-transform duration-200">
            <span className="material-symbols-outlined">add_circle</span>
            Novo Lançamento
          </Link>
        </div>
      </aside>

      <nav className="fixed bottom-0 w-full z-50 flex justify-around items-center h-20 pb-safe px-4 bg-surface-container-lowest/40 backdrop-blur-lg border-t border-white/10 shadow-[0_-10px_30px_rgba(0,0,0,0.3)] md:hidden rounded-t-xl">
        <Link href="/" className={`flex flex-col items-center justify-center transition-transform tap-highlight-transparent active:scale-90 ${pathname === '/' ? 'text-secondary scale-110' : 'text-on-surface-variant opacity-60'}`}>
          <span className="material-symbols-outlined">home</span>
          <span className="font-label-md text-label-md">Home</span>
        </Link>
        <Link href="/lancamentos" className={`flex flex-col items-center justify-center transition-transform tap-highlight-transparent active:scale-90 ${pathname.startsWith('/lancamentos') ? 'text-secondary scale-110' : 'text-on-surface-variant opacity-60'}`}>
          <span className="material-symbols-outlined">add_chart</span>
          <span className="font-label-md text-label-md">Lançamentos</span>
        </Link>
        <Link href="/relatorios" className={`flex flex-col items-center justify-center transition-transform tap-highlight-transparent active:scale-90 ${pathname.startsWith('/relatorios') ? 'text-secondary scale-110' : 'text-on-surface-variant opacity-60'}`}>
          <span className="material-symbols-outlined">assessment</span>
          <span className="font-label-md text-label-md">Relatórios</span>
        </Link>
        <Link href="/configuracoes" className={`flex flex-col items-center justify-center transition-transform tap-highlight-transparent active:scale-90 ${pathname.startsWith('/configuracoes') ? 'text-secondary scale-110' : 'text-on-surface-variant opacity-60'}`}>
          <span className="material-symbols-outlined">menu</span>
          <span className="font-label-md text-label-md">Menu</span>
        </Link>
      </nav>
      
      <Link href="/lancamentos" className="fixed bottom-24 right-8 z-40 bg-secondary text-on-secondary p-4 rounded-full shadow-2xl glow-emerald md:hidden active:scale-90 transition-transform">
        <span className="material-symbols-outlined">add</span>
      </Link>
    </>
  );
}
