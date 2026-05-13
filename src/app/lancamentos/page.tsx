import prisma from "@/lib/prisma";
import LancamentosClient from "./LancamentosClient";

export const dynamic = 'force-dynamic';

export default async function LancamentosPage() {
  const lancamentos = await prisma.transaction.findMany({
    orderBy: { startDate: 'desc' },
    include: {
      customer: true,
      vehicle: true
    }
  });

  const clientes = await prisma.customer.findMany({
    orderBy: { name: 'asc' }
  });

  const veiculos = await prisma.vehicle.findMany({
    orderBy: { plate: 'asc' }
  });

  return <LancamentosClient lancamentos={lancamentos} clientes={clientes} veiculos={veiculos} />;
}
