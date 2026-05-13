"use client";

import { useState } from "react";
import { createCustomer, deleteCustomer, updateCustomer } from "../actions";

export default function ClientesClient({ clientes }: { clientes: any[] }) {
  const [editingCliente, setEditingCliente] = useState<any>(null);
  const [isFormOpen, setIsFormOpen] = useState(false);

  const toggleForm = () => {
    if (isFormOpen && !editingCliente) {
      setIsFormOpen(false);
    } else {
      setEditingCliente(null);
      setIsFormOpen(true);
    }
  };

  const openEdit = (cliente: any) => {
    setEditingCliente(cliente);
    setIsFormOpen(true);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  const closeForm = () => {
    setEditingCliente(null);
    setIsFormOpen(false);
  };

  return (
    <div className="max-w-5xl mx-auto space-y-8 animate-fade-in">
      <header className="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
          <h1 className="font-headline-xl text-headline-xl text-on-surface">Clientes</h1>
          <p className="text-on-surface-variant font-body-md">Gerencie o cadastro de clientes e parceiros financeiros.</p>
        </div>
        <div className="flex gap-3">
          <button onClick={toggleForm} className="flex items-center gap-2 px-6 py-2 glass-card rounded-full text-on-surface hover:bg-white/10 transition-colors">
            <span className="material-symbols-outlined text-[20px]">{isFormOpen ? 'close' : 'add'}</span>
            {isFormOpen ? 'Cancelar' : 'Novo Cliente'}
          </button>
        </div>
      </header>

      {isFormOpen && (
        <section className="glass-card rounded-xl p-8 shadow-2xl relative overflow-hidden animate-fade-in">
          <div className="absolute -top-24 -right-24 w-64 h-64 bg-secondary/5 rounded-full blur-[80px]"></div>
          <h2 className="font-headline-lg text-headline-lg text-secondary mb-8">
            {editingCliente ? "Editar Cliente" : "Novo Cadastro"}
          </h2>
          <form action={async (formData) => {
            if (editingCliente) {
              await updateCustomer(editingCliente.id, formData);
            } else {
              await createCustomer(formData);
            }
            closeForm();
          }} className="grid grid-cols-1 md:grid-cols-2 gap-gutter">
            <div className="space-y-2">
              <label className="font-label-md text-label-md text-on-surface-variant ml-1">Nome</label>
              <input name="name" required defaultValue={editingCliente?.name || ""} className="w-full bg-white/5 border-b-2 border-white/10 focus:border-secondary focus:ring-0 text-on-surface placeholder:text-on-surface-variant/30 rounded-t-lg transition-all py-3 px-4 outline-none" placeholder="Ex: João Silva" type="text" />
            </div>
            <div className="space-y-2">
              <label className="font-label-md text-label-md text-on-surface-variant ml-1">CPF/CNPJ</label>
              <input name="document" required defaultValue={editingCliente?.document || ""} className="w-full bg-white/5 border-b-2 border-white/10 focus:border-secondary focus:ring-0 text-on-surface placeholder:text-on-surface-variant/30 rounded-t-lg transition-all py-3 px-4 outline-none" placeholder="000.000.000-00" type="text" />
            </div>
            <div className="space-y-2">
              <label className="font-label-md text-label-md text-on-surface-variant ml-1">Telefone</label>
              <input name="phone" defaultValue={editingCliente?.phone || ""} className="w-full bg-white/5 border-b-2 border-white/10 focus:border-secondary focus:ring-0 text-on-surface placeholder:text-on-surface-variant/30 rounded-t-lg transition-all py-3 px-4 outline-none" placeholder="(11) 99999-9999" type="tel" />
            </div>
            <div className="space-y-2">
              <label className="font-label-md text-label-md text-on-surface-variant ml-1">E-mail</label>
              <input name="email" type="email" defaultValue={editingCliente?.email || ""} className="w-full bg-white/5 border-b-2 border-white/10 focus:border-secondary focus:ring-0 text-on-surface placeholder:text-on-surface-variant/30 rounded-t-lg transition-all py-3 px-4 outline-none" placeholder="cliente@email.com" />
            </div>
            <div className="md:col-span-2 flex justify-end pt-4 gap-4">
              {editingCliente && (
                <button type="button" onClick={closeForm} className="bg-white/5 text-on-surface px-10 py-4 rounded-xl font-bold hover:bg-white/10 transition-all duration-200">
                  Cancelar
                </button>
              )}
              <button className="bg-secondary text-on-secondary px-10 py-4 rounded-xl font-bold glow-secondary hover:scale-[1.02] active:scale-95 transition-all duration-200 flex items-center gap-2" type="submit">
                <span className="material-symbols-outlined">{editingCliente ? 'save' : 'person_add'}</span>
                {editingCliente ? "Atualizar" : "Cadastrar Cliente"}
              </button>
            </div>
          </form>
        </section>
      )}

      <section className="space-y-4">
        <h3 className="font-headline-lg text-headline-lg text-on-surface flex items-center gap-3">
          <span className="material-symbols-outlined text-secondary">list_alt</span>
          Clientes Cadastrados
        </h3>
        
        {clientes.length > 0 ? (
          <div className="glass-card rounded-xl overflow-hidden overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="bg-white/5 border-b border-white/10">
                  <th className="px-6 py-4 font-label-md text-label-md text-secondary">NOME</th>
                  <th className="px-6 py-4 font-label-md text-label-md text-secondary">CPF/CNPJ</th>
                  <th className="px-6 py-4 font-label-md text-label-md text-secondary">E-MAIL</th>
                  <th className="px-6 py-4 font-label-md text-label-md text-secondary text-right">AÇÕES</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {clientes.map((cliente, i) => (
                  <tr key={cliente.id} className="hover:bg-white/5 transition-colors group">
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-3">
                        <div className={`w-10 h-10 rounded-full flex items-center justify-center font-bold ${
                          i % 3 === 0 ? 'bg-primary/20 text-primary' : 
                          i % 3 === 1 ? 'bg-secondary/20 text-secondary' : 
                          'bg-tertiary/20 text-tertiary'
                        }`}>
                          {cliente.name.substring(0, 2).toUpperCase()}
                        </div>
                        <span className="font-medium">{cliente.name}</span>
                      </div>
                    </td>
                    <td className="px-6 py-4 text-on-surface-variant">{cliente.document}</td>
                    <td className="px-6 py-4 text-on-surface-variant">{cliente.email || "-"}</td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onClick={() => openEdit(cliente)} className="p-2 hover:bg-white/10 rounded-lg text-secondary">
                          <span className="material-symbols-outlined text-[20px]">edit</span>
                        </button>
                        <button onClick={() => { if(confirm("Tem certeza que deseja excluir?")) deleteCustomer(cliente.id) }} className="p-2 hover:bg-error/10 rounded-lg text-error">
                          <span className="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : (
          <div className="glass-card rounded-xl p-12 text-center text-on-surface-variant">
            <span className="material-symbols-outlined text-4xl mb-4 opacity-50">group_off</span>
            <p>Nenhum cliente cadastrado.</p>
          </div>
        )}
      </section>
    </div>
  );
}
