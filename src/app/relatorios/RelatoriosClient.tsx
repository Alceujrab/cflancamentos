"use client";

import { useState } from "react";
import { format } from "date-fns";
import { ptBR } from "date-fns/locale";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";

export default function RelatoriosClient({ 
  lancamentos, clientes, settings 
}: { 
  lancamentos: any[], clientes: any[], settings: any 
}) {
  const [filterCliente, setFilterCliente] = useState<string>("ALL");
  const [filterTipo, setFilterTipo] = useState<string>("ALL");
  const [startDate, setStartDate] = useState<string>("");
  const [endDate, setEndDate] = useState<string>("");

  const [appliedFilters, setAppliedFilters] = useState({
    cliente: "ALL",
    tipo: "ALL",
    start: "",
    end: ""
  });

  const handleApplyFilters = () => {
    setAppliedFilters({
      cliente: filterCliente,
      tipo: filterTipo,
      start: startDate,
      end: endDate
    });
  };

  const filteredLancamentos = lancamentos.filter((tx) => {
    if (appliedFilters.cliente !== "ALL" && tx.customerId.toString() !== appliedFilters.cliente) return false;
    if (appliedFilters.tipo !== "ALL" && tx.type !== appliedFilters.tipo) return false;
    if (appliedFilters.start && new Date(tx.startDate) < new Date(appliedFilters.start)) return false;
    if (appliedFilters.end && new Date(tx.startDate) > new Date(appliedFilters.end)) return false;
    return true;
  });

  const totalCredit = filteredLancamentos.filter(t => t.type === "CREDIT").reduce((acc, t) => acc + t.value, 0);
  const totalDebit = filteredLancamentos.filter(t => t.type === "DEBIT").reduce((acc, t) => acc + t.value, 0);
  const balance = totalCredit - totalDebit;

  const handleExportPDF = () => {
    const doc = new jsPDF();
    doc.setFontSize(20);
    doc.text("Relatório Financeiro", 14, 22);
    doc.setFontSize(10);
    doc.setTextColor(100);
    if (settings?.cnpj) doc.text(`CNPJ: ${settings.cnpj}`, 14, 30);
    if (settings?.phone) doc.text(`Telefone: ${settings.phone}`, 14, 35);
    
    doc.setFontSize(11);
    doc.setTextColor(0);
    let y = 45;
    doc.text(`Período: ${appliedFilters.start ? format(new Date(appliedFilters.start), "dd/MM/yyyy") : "Início"} até ${appliedFilters.end ? format(new Date(appliedFilters.end), "dd/MM/yyyy") : "Hoje"}`, 14, y);
    
    const tableData = filteredLancamentos.map(tx => [
      `${format(new Date(tx.startDate), "dd/MM/yyyy")} a ${format(new Date(tx.endDate), "dd/MM/yyyy")}`,
      tx.customer.name,
      tx.description,
      tx.type === "CREDIT" ? "Entrada" : "Saída",
      new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(tx.value)
    ]);

    autoTable(doc, {
      startY: y + 10,
      head: [['Período', 'Cliente', 'Descrição', 'Tipo', 'Valor']],
      body: tableData,
      theme: 'grid',
      headStyles: { fillColor: [78, 222, 163] },
      didParseCell: function (data) {
        if (data.section === 'body' && data.column.index === 3) {
          if (data.cell.raw === 'Entrada') data.cell.styles.textColor = [22, 101, 52];
          if (data.cell.raw === 'Saída') data.cell.styles.textColor = [153, 27, 27];
        }
      }
    });

    const finalY = (doc as any).lastAutoTable.finalY || y + 20;
    doc.setFontSize(12);
    doc.setTextColor(0);
    doc.text(`Total Entradas: ${new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(totalCredit)}`, 14, finalY + 10);
    doc.text(`Total Saídas: ${new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(totalDebit)}`, 14, finalY + 18);
    
    doc.setFontSize(14);
    if (balance >= 0) doc.setTextColor(22, 101, 52);
    else doc.setTextColor(153, 27, 27);
    doc.text(`Saldo Final: ${new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(balance)}`, 14, finalY + 30);

    doc.save("relatorio_financeiro.pdf");
  };

  return (
    <div className="max-w-5xl mx-auto space-y-8 animate-fade-in">
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
          <h1 className="font-headline-xl text-headline-xl text-on-surface mb-2">Relatórios</h1>
          <p className="text-on-surface-variant font-body-md text-body-md max-w-xl">
            Gere relatórios filtrados e exporte para PDF.
          </p>
        </div>
        <div className="flex gap-4">
          <button onClick={handleExportPDF} disabled={filteredLancamentos.length === 0} className="bg-secondary text-on-secondary px-6 py-3 rounded-xl font-bold glow-secondary hover:scale-105 active:scale-95 transition-all duration-200 flex items-center gap-2 disabled:opacity-50 disabled:pointer-events-none">
            <span className="material-symbols-outlined">download</span>
            Exportar PDF
          </button>
        </div>
      </div>

      <div className="glass-card p-6 rounded-2xl shadow-2xl relative overflow-hidden">
        <div className="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-secondary to-primary opacity-50"></div>
        <h3 className="font-bold text-on-surface flex items-center gap-2 mb-6">
          <span className="material-symbols-outlined text-secondary">filter_alt</span>
          Filtros de Busca
        </h3>
        
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
          <div className="space-y-2">
            <label className="font-label-md text-label-md text-on-surface-variant">CLIENTE</label>
            <div className="relative">
              <select value={filterCliente} onChange={(e) => setFilterCliente(e.target.value)} className="w-full bg-white/5 border-b border-white/20 focus:border-secondary transition-colors py-3 px-4 rounded-t-lg appearance-none text-on-surface outline-none">
                <option value="ALL" className="bg-surface-container-high">Todos os Clientes</option>
                {clientes.map(c => <option key={c.id} value={c.id} className="bg-surface-container-high">{c.name}</option>)}
              </select>
              <span className="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none opacity-50">expand_more</span>
            </div>
          </div>
          
          <div className="space-y-2">
            <label className="font-label-md text-label-md text-on-surface-variant">TIPO</label>
            <div className="relative">
              <select value={filterTipo} onChange={(e) => setFilterTipo(e.target.value)} className="w-full bg-white/5 border-b border-white/20 focus:border-secondary transition-colors py-3 px-4 rounded-t-lg appearance-none text-on-surface outline-none">
                <option value="ALL" className="bg-surface-container-high">Todos</option>
                <option value="CREDIT" className="bg-surface-container-high">Apenas Entradas</option>
                <option value="DEBIT" className="bg-surface-container-high">Apenas Saídas</option>
              </select>
              <span className="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none opacity-50">expand_more</span>
            </div>
          </div>

          <div className="space-y-2">
            <label className="font-label-md text-label-md text-on-surface-variant">DATA INICIAL</label>
            <input type="date" value={startDate} onChange={(e) => setStartDate(e.target.value)} className="w-full bg-white/5 border-b border-white/20 focus:border-secondary transition-colors py-3 px-4 rounded-t-lg text-on-surface outline-none" />
          </div>

          <div className="flex gap-2">
            <div className="space-y-2 flex-1">
              <label className="font-label-md text-label-md text-on-surface-variant">DATA FINAL</label>
              <input type="date" value={endDate} onChange={(e) => setEndDate(e.target.value)} className="w-full bg-white/5 border-b border-white/20 focus:border-secondary transition-colors py-3 px-4 rounded-t-lg text-on-surface outline-none" />
            </div>
            <button onClick={handleApplyFilters} className="bg-white/10 hover:bg-white/20 text-on-surface h-[52px] w-[52px] rounded-t-lg border-b border-white/20 flex items-center justify-center transition-colors">
              <span className="material-symbols-outlined text-secondary">search</span>
            </button>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-gutter">
        <div className="glass-card p-6 rounded-2xl glow-primary">
          <div className="text-on-surface-variant text-sm mb-2">Total Entradas (Filtro)</div>
          <div className="font-stats-lg text-2xl text-secondary">
            {new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(totalCredit)}
          </div>
        </div>
        <div className="glass-card p-6 rounded-2xl glow-error">
          <div className="text-on-surface-variant text-sm mb-2">Total Saídas (Filtro)</div>
          <div className="font-stats-lg text-2xl text-error">
            {new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(totalDebit)}
          </div>
        </div>
        <div className="glass-card p-6 rounded-2xl glow-secondary">
          <div className="text-on-surface-variant text-sm mb-2">Saldo Líquido (Filtro)</div>
          <div className={`font-stats-lg text-2xl ${balance >= 0 ? 'text-secondary' : 'text-error'}`}>
            {new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(balance)}
          </div>
        </div>
      </div>

      <div className="glass-card rounded-3xl overflow-hidden border-white/5">
        <div className="p-6 flex items-center justify-between border-b border-white/5">
          <h2 className="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Resultados da Busca</h2>
        </div>
        
        {filteredLancamentos.length > 0 ? (
          <div className="overflow-x-auto">
            <table className="w-full border-collapse">
              <thead>
                <tr className="text-left text-on-surface-variant font-label-md border-b border-white/5">
                  <th className="px-6 py-4 font-medium">PERÍODO</th>
                  <th className="px-6 py-4 font-medium">CLIENTE / DESCRIÇÃO</th>
                  <th className="px-6 py-4 font-medium">VALOR</th>
                  <th className="px-6 py-4 font-medium text-center">TIPO</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/5">
                {filteredLancamentos.map((tx: any) => (
                  <tr key={tx.id} className="hover:bg-white/5 transition-colors group">
                    <td className="px-6 py-5 text-on-surface-variant font-label-md whitespace-nowrap">
                      {format(new Date(tx.startDate), "dd/MM/yyyy", { locale: ptBR })}<br/>
                      até {format(new Date(tx.endDate), "dd/MM/yyyy", { locale: ptBR })}
                    </td>
                    <td className="px-6 py-5">
                      <div className="flex flex-col">
                        <span className="text-on-surface font-semibold">{tx.customer.name} - {tx.description}</span>
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
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : (
          <div className="p-12 flex flex-col items-center justify-center text-on-surface-variant">
            <span className="material-symbols-outlined text-4xl mb-4 opacity-50">search_off</span>
            <p>Nenhum resultado encontrado para os filtros.</p>
          </div>
        )}
      </div>
    </div>
  );
}
