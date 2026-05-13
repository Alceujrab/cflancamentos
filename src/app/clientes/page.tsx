import prisma from "@/lib/prisma";
import ClientesClient from "./ClientesClient";

export const dynamic = 'force-dynamic';

export default async function ClientesPage() {
  const clientes = await prisma.customer.findMany({
    orderBy: { createdAt: 'desc' }
  });

  return <ClientesClient clientes={clientes} />;
}
