import prisma from "@/lib/prisma";
import { format } from "date-fns";
import { ptBR } from "date-fns/locale";
import Link from "next/link";

export const dynamic = 'force-dynamic';

export default async function Dashboard() {
  const transactions = await prisma.transaction.findMany({
    orderBy: { startDate: "desc" },
    take: 5,
    include: { customer: true, vehicle: true },
  });

  const allTransactions = await prisma.transaction.findMany();
  
  const totalCredit = allTransactions
    .filter((t: any) => t.type === "CREDIT")
    .reduce((acc: number, t: any) => acc + t.value, 0);
    
  const totalDebit = allTransactions
    .filter((t: any) => t.type === "DEBIT")
    .reduce((acc: number, t: any) => acc + t.value, 0);
    
  const balance = totalCredit - totalDebit;

  return (
    <div className="max-w-7xl mx-auto space-y-gutter animate-fade-in">
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
          <h1 className="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">Visão Geral</h1>
          <p className="text-on-surface-variant font-body-md">Bem-vindo de volta. Aqui está o resumo das suas finanças.</p>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-gutter">
        <div className="glass-card p-8 rounded-3xl glow-primary flex flex-col justify-between relative overflow-hidden group">
          <div className="absolute top-0 right-0 w-32 h-32 bg-primary/10 blur-[80px] -mr-16 -mt-16"></div>
          <div>
            <div className="flex items-center gap-2 text-on-surface-variant mb-4">
              <span className="material-symbols-outlined text-primary">account_balance_wallet</span>
              <span className="font-label-md">SALDO TOTAL</span>
            </div>
            <div className="font-stats-lg text-stats-lg text-on-surface tracking-tight">
              {new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(balance)}
            </div>
          </div>
          <div className="mt-6 flex items-center gap-2 text-secondary text-label-md">
            <span className="material-symbols-outlined text-sm">{balance >= 0 ? 'trending_up' : 'trending_down'}</span>
            <span>Atualizado agora</span>
          </div>
        </div>

        <div className="glass-card p-8 rounded-3xl glow-emerald flex flex-col justify-between border-secondary/20">
          <div>
            <div className="flex items-center gap-2 text-secondary mb-4">
              <span className="material-symbols-outlined" style={{ fontVariationSettings: "'FILL' 1" }}>arrow_circle_up</span>
              <span className="font-label-md">ENTRADAS TOTAIS</span>
            </div>
            <div className="font-stats-lg text-stats-lg text-secondary tracking-tight">
              {new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(totalCredit)}
            </div>
          </div>
          <div className="mt-6 flex items-center gap-2 text-on-surface-variant text-label-md">
            <span className="material-symbols-outlined text-sm">event</span>
            <span>Total geral de entradas</span>
          </div>
        </div>

        <div className="glass-card p-8 rounded-3xl glow-coral flex flex-col justify-between border-on-tertiary-container/20">
          <div>
            <div className="flex items-center gap-2 text-on-tertiary-container mb-4">
              <span className="material-symbols-outlined" style={{ fontVariationSettings: "'FILL' 1" }}>arrow_circle_down</span>
              <span className="font-label-md">SAÍDAS TOTAIS</span>
            </div>
            <div className="font-stats-lg text-stats-lg text-on-tertiary-container tracking-tight">
              {new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(totalDebit)}
            </div>
          </div>
          <div className="mt-6 flex items-center gap-2 text-on-surface-variant text-label-md">
            <span className="material-symbols-outlined text-sm">schedule</span>
            <span>Total geral de saídas</span>
          </div>
        </div>
      </div>

      <div className="glass-card rounded-3xl overflow-hidden border-white/5">
        <div className="p-6 flex items-center justify-between border-b border-white/5">
          <h2 className="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Lançamentos Recentes</h2>
          <Link href="/lancamentos" className="text-primary font-label-md hover:underline flex items-center gap-1">
            Ver tudo <span className="material-symbols-outlined text-sm">chevron_right</span>
          </Link>
        </div>
        
        {transactions.length > 0 ? (
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
                {transactions.map((tx: any) => (
                  <tr key={tx.id} className="hover:bg-white/5 transition-colors cursor-pointer group">
                    <td className="px-6 py-5 text-on-surface-variant font-label-md whitespace-nowrap">
                      {format(new Date(tx.startDate), "dd/MM/yyyy", { locale: ptBR })}<br/>
                      até {format(new Date(tx.endDate), "dd/MM/yyyy", { locale: ptBR })}
                    </td>
                    <td className="px-6 py-5">
                      <div className="flex items-center gap-3">
                        <div className={`w-10 h-10 rounded-full glass-card flex items-center justify-center font-bold ${tx.type === "CREDIT" ? "text-secondary" : "text-tertiary"}`}>
                          {tx.customer.name.substring(0, 2).toUpperCase()}
                        </div>
                        <div className="flex flex-col">
                          <span className="text-on-surface font-semibold">{tx.customer.name} - {tx.description}</span>
                          <span className="text-on-surface-variant text-xs opacity-60">
                            {tx.vehicle ? tx.vehicle.name : (tx.observation || "Sem observação")}
                          </span>
                        </div>
                      </div>
                    </td>
                    <td className={`px-6 py-5 font-bold ${tx.type === "CREDIT" ? "text-secondary" : "text-on-tertiary-container"}`}>
                      {new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(tx.value)}
                    </td>
                    <td className="px-6 py-5 text-center">
                      <span className={`text-xs px-3 py-1 rounded-full border ${tx.type === "CREDIT" ? "bg-secondary/10 text-secondary border-secondary/20" : "bg-tertiary-container/30 text-on-tertiary-container border-on-tertiary-container/20"}`}>
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
            <span className="material-symbols-outlined text-4xl mb-4 opacity-50">receipt_long</span>
            <p>Nenhum lançamento registrado ainda.</p>
          </div>
        )}
      </div>
    </div>
  );
}
