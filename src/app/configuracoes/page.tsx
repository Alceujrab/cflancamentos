import prisma from "@/lib/prisma";
import ConfigClient from "./ConfigClient";

export const dynamic = 'force-dynamic';

export default async function ConfigPage() {
  const settings = await prisma.settings.findFirst();

  return <ConfigClient settings={settings} />;
}
