import type { Metadata } from "next";
import "./globals.css";
import Navigation from "@/components/Navigation";

export const metadata: Metadata = {
  title: "CFLançamentos - Controle Financeiro",
  description: "Sistema de Controle Financeiro com Gestão de Veículos",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="pt-BR" className="dark" suppressHydrationWarning>
      <head>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <script dangerouslySetInnerHTML={{ __html: `
          tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container-low": "#0d1c2d",
                        "error": "#ffb4ab",
                        "tertiary-container": "#39000b",
                        "primary-container": "#0f172a",
                        "on-tertiary-container": "#ee3a5a",
                        "surface-bright": "#2c3a4c",
                        "secondary-fixed-dim": "#4edea3",
                        "inverse-primary": "#565e74",
                        "secondary-container": "#00a572",
                        "on-primary-fixed-variant": "#3f465c",
                        "primary-fixed": "#dae2fd",
                        "error-container": "#93000a",
                        "outline": "#909097",
                        "on-background": "#d4e4fa",
                        "on-secondary-fixed": "#002113",
                        "surface-container": "#122131",
                        "on-tertiary-fixed-variant": "#92002a",
                        "tertiary-fixed-dim": "#ffb2b7",
                        "on-primary-container": "#798098",
                        "on-surface-variant": "#c6c6cd",
                        "surface-container-high": "#1c2b3c",
                        "surface-tint": "#bec6e0",
                        "on-secondary-fixed-variant": "#005236",
                        "primary": "#bec6e0",
                        "on-error-container": "#ffdad6",
                        "on-tertiary-fixed": "#40000d",
                        "inverse-surface": "#d4e4fa",
                        "inverse-on-surface": "#233143",
                        "background": "#051424",
                        "surface-container-highest": "#273647",
                        "surface": "#051424",
                        "primary-fixed-dim": "#bec6e0",
                        "surface-dim": "#051424",
                        "on-secondary-container": "#00311f",
                        "on-error": "#690005",
                        "on-primary": "#283044",
                        "on-surface": "#d4e4fa",
                        "tertiary": "#ffb2b7",
                        "surface-variant": "#273647",
                        "on-tertiary": "#67001b",
                        "on-primary-fixed": "#131b2e",
                        "surface-container-lowest": "#010f1f",
                        "tertiary-fixed": "#ffdadb",
                        "on-secondary": "#003824",
                        "outline-variant": "#45464d",
                        "secondary-fixed": "#6ffbbe",
                        "secondary": "#4edea3"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "base": "8px",
                        "container-padding": "32px",
                        "gutter": "24px",
                        "glass-margin": "16px"
                    },
                    "fontFamily": {
                        "headline-xl": ["Manrope"],
                        "headline-lg": ["Manrope"],
                        "headline-lg-mobile": ["Manrope"],
                        "label-md": ["JetBrains Mono"],
                        "stats-lg": ["Manrope"],
                        "body-md": ["Manrope"]
                    },
                    "fontSize": {
                        "headline-xl": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.05em", "fontWeight": "500"}],
                        "stats-lg": ["40px", {"lineHeight": "48px", "fontWeight": "800"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}]
                    }
                }
            }
          }
        `}}></script>
      </head>
      <body className="bg-background text-on-surface font-body-md selection:bg-secondary/30">
        <Navigation />
        <main className="md:ml-64 pt-24 px-container-padding pb-32">
          {children}
        </main>
      </body>
    </html>
  );
}
