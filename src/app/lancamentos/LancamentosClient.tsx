"use client";

import { useState } from "react";
import { format } from "date-fns";
import { ptBR } from "date-fns/locale";
import { createTransaction, deleteTransaction, updateTransaction } from "../actions";

export default function LancamentosClient({ 
  lancamentos, clientes, veiculos 
}: { 
  lancamentos: any[], clientes: any[], veiculos: any[] 
}) {
  const [editingTransaction, setEditingTransaction] = useState<any>(null);
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [type, setType] = useState("CREDIT");

  const totalCredit = lancamentos.filter(t => t.type === "CREDIT").reduce((acc, t) => acc + t.value, 0);
  const totalDebit = lancamentos.filter(t => t.type === "DEBIT").reduce((acc, t) => acc + t.value, 0);
  const balance = totalCredit - totalDebit;

  const toggleForm = () => {
    if (isFormOpen && !editingTransaction) {
      setIsFormOpen(false);
    } else {
      setEditingTransaction(null);
      setType("CREDIT");
      setIsFormOpen(true);
    }
  };

  const openEdit = (tx: any) => {
    setEditingTransaction(tx);
    setType(tx.type);
    setIsFormOpen(true);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  const closeForm = () => {
    setEditingTransaction(null);
    setIsFormOpen(false);
  };

  return (
    <div className="max-w-5xl mx-auto space-y-8 animate-fade-in">
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
          <h1 className="font-headline-xl text-headline-xl text-on-surface mb-2">Lançamentos</h1>
          <p className="text-on-surface-variant font-body-md text-body-md max-w-xl">
            Registre transações financeiras vinculadas a clientes e veículos.
          </p>
        </div>
        <div className="flex gap-4">
          <div className="glass-card px-6 py-3 rounded-xl flex flex-col items-center justify-center min-w-[140px]">
            <span className="text-on-surface-variant text-xs font-label-md uppercase tracking-widest mb-1">Saldo Atual</span>
            <span className={`font-stats-lg text-2xl ${balance >= 0 ? 'text-secondary' : 'text-on-tertiary-container'}`}>
              {new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(balance)}
            </span>
          </div>
          <button onClick={toggleForm} className="bg-secondary text-on-secondary px-6 py-3 rounded-xl font-bold glow-secondary hover:scale-105 active:scale-95 transition-all duration-200 flex items-center gap-2">
            <span className="material-symbols-outlined">{isFormOpen ? 'close' : 'add'}</span>
            {isFormOpen ? 'Cancelar' : 'Novo Lançamento'}
          </button>
        </div>
      </div>

      {isFormOpen && (
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-gutter animate-fade-in">
          <div className="lg:col-span-8 flex flex-col gap-gutter">
            <div className="glass-card p-8 rounded-2xl shadow-2xl relative overflow-hidden">
              <div className={`absolute top-0 left-0 w-1 h-full opacity-50 ${type === 'CREDIT' ? 'bg-secondary' : 'bg-error'}`}></div>
              <form action={async (formData) => {
                formData.append("type", type);
                if (editingTransaction) {
                  await updateTransaction(editingTransaction.id, formData);
                } else {
                  await createTransaction(formData);
                }
                closeForm();
              }} className="space-y-8">
                
                <div className="space-y-3">
                  <label className="font-label-md text-label-md text-on-surface-variant">TIPO DE OPERAÇÃO</label>
                  <div className="grid grid-cols-2 p-1 bg-surface-container-low/50 rounded-xl border border-white/5">
                    <button onClick={() => setType("CREDIT")} className={`flex items-center justify-center gap-2 py-3 rounded-lg transition-all ${type === "CREDIT" ? "bg-secondary/10 text-secondary border border-secondary/20 glow-secondary" : "text-on-surface-variant hover:bg-white/5"}`} type="button">
                      <span className="material-symbols-outlined">add_circle</span>
                      <span className="font-bold">Crédito (Entrada)</span>
                    </button>
                    <button onClick={() => setType("DEBIT")} className={`flex items-center justify-center gap-2 py-3 rounded-lg transition-all ${type === "DEBIT" ? "bg-error/10 text-error border border-error/20 glow-error" : "text-on-surface-variant hover:bg-white/5"}`} type="button">
                      <span className="material-symbols-outlined">remove_circle</span>
                      <span className="font-bold">Débito (Saída)</span>
                    </button>
                  </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div className="space-y-2">
                    <label className="font-label-md text-label-md text-on-surface-variant">CLIENTE</label>
                    <div className="relative">
                      <select name="customerId" required defaultValue={editingTransaction?.customerId || ""} className="w-full bg-white/5 border-b border-white/20 focus:border-secondary transition-colors py-3 px-4 rounded-t-lg appearance-none text-on-surface outline-none">
                        <option value="" className="bg-surface-container-high">Selecione um cliente...</option>
                        {clientes.map(c => <option key={c.id} value={c.id} className="bg-surface-container-high">{c.name}</option>)}
                      </select>
                      <span className="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none opacity-50">expand_more</span>
                    </div>
                  </div>
                  <div className="space-y-2">
                    <label className="font-label-md text-label-md text-on-surface-variant">VEÍCULO (OPCIONAL)</label>
                    <div className="relative">
                      <select name="vehicleId" defaultValue={editingTransaction?.vehicleId || ""} className="w-full bg-white/5 border-b border-white/20 focus:border-secondary transition-colors py-3 px-4 rounded-t-lg appearance-none text-on-surface outline-none">
                        <option value="" className="bg-surface-container-high">Sem veículo vinculado</option>
                        {veiculos.map(v => <option key={v.id} value={v.id} className="bg-surface-container-high">{v.plate} - {v.name}</option>)}
                      </select>
                      <span className="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none opacity-50">expand_more</span>
                    </div>
                  </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div className="space-y-2">
                    <label className="font-label-md text-label-md text-on-surface-variant">PERÍODO (INÍCIO - FIM)</label>
                    <div className="flex gap-2">
                      <input name="startDate" required defaultValue={editingTransaction ? new Date(editingTransaction.startDate).toISOString().split('T')[0] : new Date().toISOString().split('T')[0]} className="w-full bg-white/5 border-b border-white/20 focus:border-secondary transition-colors py-3 px-4 rounded-t-lg text-on-surface outline-none" type="date" />
                      <input name="endDate" required defaultValue={editingTransaction ? new Date(editingTransaction.endDate).toISOString().split('T')[0] : new Date().toISOString().split('T')[0]} className="w-full bg-white/5 border-b border-white/20 focus:border-secondary transition-colors py-3 px-4 rounded-t-lg text-on-surface outline-none" type="date" />
                    </div>
                  </div>
                  <div className="space-y-2">
                    <label className="font-label-md text-label-md text-on-surface-variant">VALOR (R$)</label>
                    <input name="value" required step="0.01" defaultValue={editingTransaction?.value || ""} className={`w-full bg-white/5 border-b border-white/20 focus:border-secondary transition-colors py-3 px-4 rounded-t-lg text-xl font-bold outline-none placeholder:text-white/10 ${type === 'CREDIT' ? 'text-secondary' : 'text-error'}`} placeholder="0,00" type="number" />
                  </div>
                </div>

                <div className="space-y-2">
                  <label className="font-label-md text-label-md text-on-surface-variant">DESCRIÇÃO</label>
                  <input name="description" required defaultValue={editingTransaction?.description || ""} className="w-full bg-white/5 border-b border-white/20 focus:border-secondary transition-colors py-3 px-4 rounded-t-lg text-on-surface outline-none placeholder:text-white/10" placeholder="Ex: Pagamento de Parcela" type="text" />
                </div>
                
                <div className="space-y-2">
                  <label className="font-label-md text-label-md text-on-surface-variant">ANEXAR DOCUMENTO</label>
                  <input name="documentFile" type="file" accept="image/*,.pdf" className="w-full bg-white/5 border-b border-white/20 focus:border-secondary transition-colors py-3 px-4 rounded-t-lg text-on-surface outline-none" />
                  {editingTransaction?.documentUrl && (
                    <p className="text-sm text-secondary mt-1">Anexo atual mantido se um novo não for enviado.</p>
                  )}
                </div>

                <div className="space-y-2">
                  <label className="font-label-md text-label-md text-on-surface-variant">OBSERVAÇÃO</label>
                  <textarea name="observation" defaultValue={editingTransaction?.observation || ""} className="w-full bg-white/5 border-b border-white/20 focus:border-secondary transition-colors py-3 px-4 rounded-t-lg text-on-surface outline-none placeholder:text-white/10 resize-none" placeholder="Informações adicionais relevantes..." rows={2}></textarea>
                </div>

                <div className="flex items-center justify-end gap-4 pt-6 border-t border-white/5">
                  <button onClick={closeForm} className="px-8 py-3 rounded-xl text-on-surface-variant hover:text-on-surface font-bold transition-colors" type="button">Cancelar</button>
                  <button className={`text-on-surface px-10 py-3 rounded-xl font-bold hover:scale-105 active:scale-95 transition-all duration-200 ${type === 'CREDIT' ? 'bg-secondary text-on-secondary glow-secondary' : 'bg-error text-white glow-error'}`} type="submit">
                    {editingTransaction ? 'Atualizar' : 'Confirmar Lançamento'}
                  </button>
                </div>
              </form>
            </div>
          </div>

          <div className="lg:col-span-4 flex flex-col gap-gutter">
            <div className="glass-card p-6 rounded-2xl flex flex-col gap-6">
              <h3 className="font-bold text-on-surface flex items-center gap-2">
                <span className="material-symbols-outlined text-secondary">trending_up</span>
                Resumo Geral
              </h3>
              <div className="space-y-4">
                <div className="flex justify-between items-center p-3 rounded-xl bg-white/5">
                  <span className="text-on-surface-variant text-sm">Entradas</span>
                  <span className="text-secondary font-bold">{new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(totalCredit)}</span>
                </div>
                <div className="flex justify-between items-center p-3 rounded-xl bg-white/5">
                  <span className="text-on-surface-variant text-sm">Saídas</span>
                  <span className="text-error font-bold">{new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(totalDebit)}</span>
                </div>
                <div className="pt-4 border-t border-white/10">
                  <div className="flex justify-between items-center">
                    <span className="text-on-surface font-bold">Saldo Total</span>
                    <span className={`font-stats-lg text-xl ${balance >= 0 ? 'text-secondary' : 'text-error'}`}>
                      {new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(balance)}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}

      <div className="glass-card rounded-3xl overflow-hidden border-white/5">
        <div className="p-6 flex items-center justify-between border-b border-white/5">
          <h2 className="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Histórico de Lançamentos</h2>
        </div>
        
        {lancamentos.length > 0 ? (
          <div className="overflow-x-auto">
            <table className="w-full border-collapse">
              <thead>
                <tr className="text-left text-on-surface-variant font-label-md border-b border-white/5">
                  <th className="px-6 py-4 font-medium">PERÍODO</th>
                  <th className="px-6 py-4 font-medium">DESCRIÇÃO</th>
                  <th className="px-6 py-4 font-medium">VALOR</th>
                  <th className="px-6 py-4 font-medium text-center">TIPO</th>
                  <th className="px-6 py-4 font-medium text-right">AÇÕES</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {lancamentos.map((tx: any) => (
                  <tr key={tx.id} className="hover:bg-white/5 transition-colors group">
                    <td className="px-6 py-5 text-on-surface-variant font-label-md whitespace-nowrap">
                      {format(new Date(tx.startDate), "dd/MM/yyyy", { locale: ptBR })}<br/>
                      até {format(new Date(tx.endDate), "dd/MM/yyyy", { locale: ptBR })}
                    </td>
                    <td className="px-6 py-5">
                      <div className="flex flex-col">
                        <span className="text-on-surface font-semibold">{tx.customer.name} - {tx.description}</span>
                        <span className="text-on-surface-variant text-xs opacity-60">
                          {tx.vehicle ? `Veículo: ${tx.vehicle.plate}` : (tx.observation || "Sem observação")}
                        </span>
                      </div>
                    </td>
                    <td className={`px-6 py-5 font-bold ${tx.type === "CREDIT" ? "text-secondary" : "text-error"}`}>
                      {new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(tx.value)}
                    </td>
                    <td className="px-6 py-5 text-center">
                      <span className={`text-xs px-3 py-1 rounded-full border ${tx.type === "CREDIT" ? "bg-secondary/10 text-secondary border-secondary/20" : "bg-error/10 text-error border-error/20"}`}>
                        {tx.type === "CREDIT" ? "Receita" : "Despesa"}
                      </span>
                    </td>
                    <td className="px-6 py-5 text-right">
                      <div className="flex justify-end items-center gap-2">
                        {tx.documentUrl && (
                          <a href={tx.documentUrl} target="_blank" rel="noreferrer" className="p-2 hover:bg-white/10 rounded-lg text-on-surface-variant" title="Ver anexo">
                            <span className="material-symbols-outlined text-[20px]">attachment</span>
                          </a>
                        )}
                        <button onClick={() => openEdit(tx)} className="p-2 hover:bg-white/10 rounded-lg text-secondary" title="Editar">
                          <span className="material-symbols-outlined text-[20px]">edit</span>
                        </button>
                        <button onClick={() => { if(confirm("Deseja excluir este lançamento?")) deleteTransaction(tx.id) }} className="p-2 hover:bg-error/10 rounded-lg text-error" title="Excluir">
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
          <div className="p-12 flex flex-col items-center justify-center text-on-surface-variant">
            <span className="material-symbols-outlined text-4xl mb-4 opacity-50">receipt_long</span>
            <p>Nenhum lançamento registrado.</p>
          </div>
        )}
      </div>
    </div>
  );
}
