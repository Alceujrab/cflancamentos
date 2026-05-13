"use client";

import { saveSettings } from "../actions";

export default function ConfigClient({ settings }: { settings: any }) {
  return (
    <div className="max-w-3xl mx-auto space-y-8 animate-fade-in">
      <header className="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
          <h1 className="font-headline-xl text-headline-xl text-on-surface">Configurações</h1>
          <p className="text-on-surface-variant font-body-md">Gerencie os dados da sua empresa.</p>
        </div>
      </header>

      <section className="glass-card rounded-xl p-8 shadow-2xl relative overflow-hidden">
        <div className="absolute -top-24 -right-24 w-64 h-64 bg-secondary/5 rounded-full blur-[80px]"></div>
        <h2 className="font-headline-lg text-headline-lg text-secondary mb-8">Dados da Empresa</h2>
        
        <form action={saveSettings} className="grid grid-cols-1 md:grid-cols-2 gap-gutter">
          <div className="space-y-2">
            <label className="font-label-md text-label-md text-on-surface-variant ml-1">CNPJ</label>
            <input name="cnpj" required defaultValue={settings?.cnpj || ""} className="w-full bg-white/5 border-b-2 border-white/10 focus:border-secondary focus:ring-0 text-on-surface placeholder:text-on-surface-variant/30 rounded-t-lg transition-all py-3 px-4 outline-none" placeholder="00.000.000/0001-00" type="text" />
          </div>
          <div className="space-y-2">
            <label className="font-label-md text-label-md text-on-surface-variant ml-1">Telefone</label>
            <input name="phone" defaultValue={settings?.phone || ""} className="w-full bg-white/5 border-b-2 border-white/10 focus:border-secondary focus:ring-0 text-on-surface placeholder:text-on-surface-variant/30 rounded-t-lg transition-all py-3 px-4 outline-none" placeholder="(00) 00000-0000" type="tel" />
          </div>
          <div className="space-y-2 md:col-span-2">
            <label className="font-label-md text-label-md text-on-surface-variant ml-1">Endereço</label>
            <input name="address" defaultValue={settings?.address || ""} className="w-full bg-white/5 border-b-2 border-white/10 focus:border-secondary focus:ring-0 text-on-surface placeholder:text-on-surface-variant/30 rounded-t-lg transition-all py-3 px-4 outline-none" placeholder="Rua Exemplo, 123" type="text" />
          </div>
          <div className="space-y-2 md:col-span-2">
            <label className="font-label-md text-label-md text-on-surface-variant ml-1">E-mail</label>
            <input name="email" type="email" defaultValue={settings?.email || ""} className="w-full bg-white/5 border-b-2 border-white/10 focus:border-secondary focus:ring-0 text-on-surface placeholder:text-on-surface-variant/30 rounded-t-lg transition-all py-3 px-4 outline-none" placeholder="contato@empresa.com" />
          </div>
          <div className="md:col-span-2 flex justify-end pt-4">
            <button className="bg-secondary text-on-secondary px-10 py-4 rounded-xl font-bold glow-secondary hover:scale-[1.02] active:scale-95 transition-all duration-200 flex items-center gap-2" type="submit">
              <span className="material-symbols-outlined">save</span>
              Salvar Configurações
            </button>
          </div>
        </form>
      </section>
    </div>
  );
}
