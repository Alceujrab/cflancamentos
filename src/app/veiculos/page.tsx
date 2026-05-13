import prisma from "@/lib/prisma";
import VeiculosClient from "./VeiculosClient";

export const dynamic = 'force-dynamic';

export default async function VeiculosPage() {
  const veiculos = await prisma.vehicle.findMany({
    orderBy: { createdAt: 'desc' }
  });

  return <VeiculosClient veiculos={veiculos} />;
}
