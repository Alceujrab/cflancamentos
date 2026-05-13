"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { 
  LayoutDashboard, 
  Users, 
  Car, 
  ArrowRightLeft, 
  FileText, 
  Settings 
} from "lucide-react";

export default function Sidebar() {
  const pathname = usePathname();

  const links = [
    { href: "/", label: "Dashboard", icon: <LayoutDashboard /> },
    { href: "/clientes", label: "Clientes", icon: <Users /> },
    { href: "/veiculos", label: "Veículos", icon: <Car /> },
    { href: "/lancamentos", label: "Lançamentos", icon: <ArrowRightLeft /> },
    { href: "/relatorios", label: "Relatórios", icon: <FileText /> },
    { href: "/configuracoes", label: "Configurações", icon: <Settings /> },
  ];

  return (
    <aside className="sidebar">
      <div className="sidebar-logo">
        <div style={{
          width: 36, height: 36, borderRadius: 8, 
          background: "linear-gradient(135deg, var(--primary-color), var(--primary-hover))",
          display: "flex", alignItems: "center", justifyContent: "center",
          boxShadow: "0 4px 10px rgba(2, 132, 199, 0.3)"
        }}>
          <ArrowRightLeft color="white" size={20} />
        </div>
        <span style={{ color: "var(--primary-color)" }}>CFLançamentos</span>
      </div>
      
      <nav className="nav-links">
        {links.map((link) => {
          const isActive = pathname === link.href || (link.href !== "/" && pathname.startsWith(link.href));
          return (
            <Link 
              key={link.href} 
              href={link.href} 
              className={`nav-item ${isActive ? "active" : ""}`}
            >
              {link.icon}
              {link.label}
            </Link>
          );
        })}
      </nav>
    </aside>
  );
}
