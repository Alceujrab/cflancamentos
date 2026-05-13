"use server";

import prisma from "@/lib/prisma";
import { revalidatePath } from "next/cache";
import { writeFile, mkdir } from "fs/promises";
import { join } from "path";
import { existsSync } from "fs";

export async function createCustomer(data: FormData) {
  const name = data.get("name") as string;
  const document = data.get("document") as string;
  const phone = data.get("phone") as string;
  const email = data.get("email") as string;

  await prisma.customer.create({
    data: { name, document, phone, email },
  });

  revalidatePath("/clientes");
}

export async function updateCustomer(id: number, data: FormData) {
  const name = data.get("name") as string;
  const document = data.get("document") as string;
  const phone = data.get("phone") as string;
  const email = data.get("email") as string;

  await prisma.customer.update({
    where: { id },
    data: { name, document, phone, email },
  });

  revalidatePath("/clientes");
}

export async function deleteCustomer(id: number) {
  await prisma.customer.delete({ where: { id } });
  revalidatePath("/clientes");
}

export async function createVehicle(data: FormData) {
  const plate = data.get("plate") as string;
  const name = data.get("name") as string;
  const observation = data.get("observation") as string;

  await prisma.vehicle.create({
    data: { plate, name, observation },
  });

  revalidatePath("/veiculos");
}

export async function updateVehicle(id: number, data: FormData) {
  const plate = data.get("plate") as string;
  const name = data.get("name") as string;
  const observation = data.get("observation") as string;

  await prisma.vehicle.update({
    where: { id },
    data: { plate, name, observation },
  });

  revalidatePath("/veiculos");
}

export async function deleteVehicle(id: number) {
  await prisma.vehicle.delete({ where: { id } });
  revalidatePath("/veiculos");
}

async function handleFileUpload(file: File | null) {
  if (!file || file.size === 0 || typeof file === "string") return null;

  const bytes = await file.arrayBuffer();
  const buffer = Buffer.from(bytes);

  const uploadDir = join(process.cwd(), "public/uploads");
  if (!existsSync(uploadDir)) {
    await mkdir(uploadDir, { recursive: true });
  }

  const filename = `${Date.now()}-${file.name.replace(/[^a-zA-Z0-9.-]/g, "_")}`;
  const path = join(uploadDir, filename);
  await writeFile(path, buffer);

  return `/uploads/${filename}`;
}

export async function createTransaction(data: FormData) {
  const type = data.get("type") as string;
  const startDate = new Date(data.get("startDate") as string);
  const endDate = new Date(data.get("endDate") as string);
  const description = data.get("description") as string;
  const observation = data.get("observation") as string;
  const value = parseFloat(data.get("value") as string);
  const customerId = parseInt(data.get("customerId") as string);
  
  const vehicleIdRaw = data.get("vehicleId") as string;
  const vehicleId = vehicleIdRaw ? parseInt(vehicleIdRaw) : null;

  const file = data.get("documentFile") as File | null;
  const documentUrl = await handleFileUpload(file);

  await prisma.transaction.create({
    data: {
      type,
      startDate,
      endDate,
      description,
      observation,
      value,
      customerId,
      vehicleId,
      documentUrl,
    },
  });

  revalidatePath("/lancamentos");
  revalidatePath("/");
}

export async function updateTransaction(id: number, data: FormData) {
  const type = data.get("type") as string;
  const startDate = new Date(data.get("startDate") as string);
  const endDate = new Date(data.get("endDate") as string);
  const description = data.get("description") as string;
  const observation = data.get("observation") as string;
  const value = parseFloat(data.get("value") as string);
  const customerId = parseInt(data.get("customerId") as string);
  
  const vehicleIdRaw = data.get("vehicleId") as string;
  const vehicleId = vehicleIdRaw ? parseInt(vehicleIdRaw) : null;

  const file = data.get("documentFile") as File | null;
  const documentUrl = await handleFileUpload(file);

  const updateData: any = {
    type,
    startDate,
    endDate,
    description,
    observation,
    value,
    customerId,
    vehicleId,
  };

  if (documentUrl) {
    updateData.documentUrl = documentUrl;
  }

  await prisma.transaction.update({
    where: { id },
    data: updateData,
  });

  revalidatePath("/lancamentos");
  revalidatePath("/");
}

export async function deleteTransaction(id: number) {
  await prisma.transaction.delete({ where: { id } });
  revalidatePath("/lancamentos");
  revalidatePath("/");
}

export async function saveSettings(data: FormData) {
  const cnpj = data.get("cnpj") as string;
  const phone = data.get("phone") as string;
  const address = data.get("address") as string;
  const email = data.get("email") as string;

  const existing = await prisma.settings.findFirst();

  if (existing) {
    await prisma.settings.update({
      where: { id: existing.id },
      data: { cnpj, phone, address, email },
    });
  } else {
    await prisma.settings.create({
      data: { cnpj, phone, address, email },
    });
  }

  revalidatePath("/configuracoes");
}
