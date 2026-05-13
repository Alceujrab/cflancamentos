"use client";

import { useState } from "react";
import { createVehicle, deleteVehicle, updateVehicle } from "../actions";

export default function VeiculosClient({ veiculos }: { veiculos: any[] }) {
  const [editingVehicle, setEditingVehicle] = useState<any>(null);
  const [isFormOpen, setIsFormOpen] = useState(false);

  const toggleForm = () => {
    if (isFormOpen && !editingVehicle) {
      setIsFormOpen(false);
    } else {
      setEditingVehicle(null);
      setIsFormOpen(true);
    }
  };

  const openEdit = (veiculo: any) => {
    setEditingVehicle(veiculo);
    setIsFormOpen(true);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  const closeForm = () => {
    setEditingVehicle(null);
    setIsFormOpen(false);
  };

  return (
    <div className="max-w-5xl mx-auto space-y-8 animate-fade-in">
      <header className="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
          <h1 className="font-headline-xl text-headline-xl text-on-surface">Veículos</h1>
          <p className="text-on-surface-variant font-body-md">Gerencie os veículos associados aos lançamentos.</p>
        </div>
        <div className="flex gap-3">
          <button onClick={toggleForm} className="flex items-center gap-2 px-6 py-2 glass-card rounded-full text-on-surface hover:bg-white/10 transition-colors">
            <span className="material-symbols-outlined text-[20px]">{isFormOpen ? 'close' : 'add'}</span>
            {isFormOpen ? 'Cancelar' : 'Novo Veículo'}
          </button>
        </div>
      </header>

      {isFormOpen && (
        <section className="glass-card rounded-xl p-8 shadow-2xl relative overflow-hidden animate-fade-in">
          <div className="absolute -top-24 -right-24 w-64 h-64 bg-secondary/5 rounded-full blur-[80px]"></div>
          <h2 className="font-headline-lg text-headline-lg text-secondary mb-8">
            {editingVehicle ? "Editar Veículo" : "Novo Veículo"}
          </h2>
          <form action={async (formData) => {
            if (editingVehicle) {
              await updateVehicle(editingVehicle.id, formData);
            } else {
              await createVehicle(formData);
            }
            closeForm();
          }} className="grid grid-cols-1 md:grid-cols-2 gap-gutter">
            <div className="space-y-2">
              <label className="font-label-md text-label-md text-on-surface-variant ml-1">Placa</label>
              <input name="plate" required defaultValue={editingVehicle?.plate || ""} className="w-full bg-white/5 border-b-2 border-white/10 focus:border-secondary focus:ring-0 text-on-surface placeholder:text-on-surface-variant/30 rounded-t-lg transition-all py-3 px-4 outline-none uppercase" placeholder="ABC-1234" type="text" />
            </div>
            <div className="space-y-2">
              <label className="font-label-md text-label-md text-on-surface-variant ml-1">Nome do Veículo</label>
              <input name="name" required defaultValue={editingVehicle?.name || ""} className="w-full bg-white/5 border-b-2 border-white/10 focus:border-secondary focus:ring-0 text-on-surface placeholder:text-on-surface-variant/30 rounded-t-lg transition-all py-3 px-4 outline-none" placeholder="Ex: Honda Civic 2020" type="text" />
            </div>
            <div className="space-y-2 md:col-span-2">
              <label className="font-label-md text-label-md text-on-surface-variant ml-1">Observação</label>
              <textarea name="observation" defaultValue={editingVehicle?.observation || ""} rows={3} className="w-full bg-white/5 border-b-2 border-white/10 focus:border-secondary focus:ring-0 text-on-surface placeholder:text-on-surface-variant/30 rounded-t-lg transition-all py-3 px-4 outline-none" placeholder="Detalhes adicionais do veículo..."></textarea>
            </div>
            <div className="md:col-span-2 flex justify-end pt-4 gap-4">
              {editingVehicle && (
                <button type="button" onClick={closeForm} className="bg-white/5 text-on-surface px-10 py-4 rounded-xl font-bold hover:bg-white/10 transition-all duration-200">
                  Cancelar
                </button>
              )}
              <button className="bg-secondary text-on-secondary px-10 py-4 rounded-xl font-bold glow-secondary hover:scale-[1.02] active:scale-95 transition-all duration-200 flex items-center gap-2" type="submit">
                <span className="material-symbols-outlined">{editingVehicle ? 'save' : 'directions_car'}</span>
                {editingVehicle ? "Atualizar" : "Salvar Veículo"}
              </button>
            </div>
          </form>
        </section>
      )}

      <section className="space-y-4">
        <h3 className="font-headline-lg text-headline-lg text-on-surface flex items-center gap-3">
          <span className="material-symbols-outlined text-secondary">list_alt</span>
          Veículos Cadastrados
        </h3>
        
        {veiculos.length > 0 ? (
          <div className="glass-card rounded-xl overflow-hidden overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="bg-white/5 border-b border-white/10">
                  <th className="px-6 py-4 font-label-md text-label-md text-secondary">PLACA</th>
                  <th className="px-6 py-4 font-label-md text-label-md text-secondary">VEÍCULO</th>
                  <th className="px-6 py-4 font-label-md text-label-md text-secondary">OBSERVAÇÃO</th>
                  <th className="px-6 py-4 font-label-md text-label-md text-secondary text-right">AÇÕES</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {veiculos.map((veiculo, i) => (
                  <tr key={veiculo.id} className="hover:bg-white/5 transition-colors group">
                    <td className="px-6 py-4">
                      <div className="inline-flex px-3 py-1 bg-white/10 text-on-surface rounded border border-white/20 font-mono tracking-widest text-sm">
                        {veiculo.plate}
                      </div>
                    </td>
                    <td className="px-6 py-4 font-medium">{veiculo.name}</td>
                    <td className="px-6 py-4 text-on-surface-variant">{veiculo.observation || "-"}</td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onClick={() => openEdit(veiculo)} className="p-2 hover:bg-white/10 rounded-lg text-secondary">
                          <span className="material-symbols-outlined text-[20px]">edit</span>
                        </button>
                        <button onClick={() => { if(confirm("Tem certeza que deseja excluir?")) deleteVehicle(veiculo.id) }} className="p-2 hover:bg-error/10 rounded-lg text-error">
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
            <span className="material-symbols-outlined text-4xl mb-4 opacity-50">no_crash</span>
            <p>Nenhum veículo cadastrado.</p>
          </div>
        )}
      </section>
    </div>
  );
}
