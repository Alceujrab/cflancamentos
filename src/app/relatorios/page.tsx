import prisma from "@/lib/prisma";
import RelatoriosClient from "./RelatoriosClient";

export const dynamic = 'force-dynamic';

export default async function RelatoriosPage() {
  const lancamentos = await prisma.transaction.findMany({
    orderBy: { startDate: 'asc' },
    include: {
      customer: true,
      vehicle: true
    }
  });

  const clientes = await prisma.customer.findMany({
    orderBy: { name: 'asc' }
  });

  const settings = await prisma.settings.findFirst();

  return <RelatoriosClient lancamentos={lancamentos} clientes={clientes} settings={settings} />;
}
