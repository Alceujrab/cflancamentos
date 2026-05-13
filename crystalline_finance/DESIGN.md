---
name: Crystalline Finance
colors:
  surface: '#051424'
  surface-dim: '#051424'
  surface-bright: '#2c3a4c'
  surface-container-lowest: '#010f1f'
  surface-container-low: '#0d1c2d'
  surface-container: '#122131'
  surface-container-high: '#1c2b3c'
  surface-container-highest: '#273647'
  on-surface: '#d4e4fa'
  on-surface-variant: '#c6c6cd'
  inverse-surface: '#d4e4fa'
  inverse-on-surface: '#233143'
  outline: '#909097'
  outline-variant: '#45464d'
  surface-tint: '#bec6e0'
  primary: '#bec6e0'
  on-primary: '#283044'
  primary-container: '#0f172a'
  on-primary-container: '#798098'
  inverse-primary: '#565e74'
  secondary: '#4edea3'
  on-secondary: '#003824'
  secondary-container: '#00a572'
  on-secondary-container: '#00311f'
  tertiary: '#ffb2b7'
  on-tertiary: '#67001b'
  tertiary-container: '#39000b'
  on-tertiary-container: '#ee3a5a'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#dae2fd'
  primary-fixed-dim: '#bec6e0'
  on-primary-fixed: '#131b2e'
  on-primary-fixed-variant: '#3f465c'
  secondary-fixed: '#6ffbbe'
  secondary-fixed-dim: '#4edea3'
  on-secondary-fixed: '#002113'
  on-secondary-fixed-variant: '#005236'
  tertiary-fixed: '#ffdadb'
  tertiary-fixed-dim: '#ffb2b7'
  on-tertiary-fixed: '#40000d'
  on-tertiary-fixed-variant: '#92002a'
  background: '#051424'
  on-background: '#d4e4fa'
  surface-variant: '#273647'
typography:
  headline-xl:
    fontFamily: Manrope
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Manrope
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Manrope
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  body-md:
    fontFamily: Manrope
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-md:
    fontFamily: JetBrains Mono
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
    letterSpacing: 0.05em
  stats-lg:
    fontFamily: Manrope
    fontSize: 40px
    fontWeight: '800'
    lineHeight: 48px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  container-padding: 32px
  gutter: 24px
  glass-margin: 16px
---

## Brand & Style
This design system is built for a high-end financial environment that prioritizes clarity, precision, and a sense of "digital prestige." The brand personality is professional yet forward-thinking, aimed at users who value sophisticated data visualization and a modern aesthetic.

The visual direction follows a refined **Glassmorphism** style. It leverages deep atmospheric backgrounds, translucent surface layers, and high-fidelity "frosted" effects to create a sense of depth and hierarchy. The UI feels like a series of etched glass panes floating in a dark, structured space, evoking a feeling of technological advancement and financial transparency.

## Colors
The palette is rooted in a "Deep Space" theme to emphasize the glass effects.
- **Primary (Deep Slate/Blue):** Used for the core background environment, providing a stable, professional foundation.
- **Secondary (Emerald Green):** Dedicated exclusively to positive financial flows, growth metrics, and "In" transactions.
- **Tertiary (Coral Ruby):** Dedicated to expenditures, alerts, and "Out" transactions.
- **Neutral (Silver Slate):** Used for secondary text, borders, and UI scaffolding.

Surface colors are not solid; they are derived from semi-transparent white or primary-tinted overlays (e.g., `rgba(255, 255, 255, 0.05)`) to achieve the glass effect.

## Typography
Typography in this design system is designed for maximum legibility against dark, blurred backgrounds. 

- **Manrope** is used for all primary communication, providing a modern, balanced, and trustworthy feel essential for financial services.
- **JetBrains Mono** is introduced for labels and transaction IDs to provide a technical, "data-first" aesthetic that aids in distinguishing numerical values from prose.
- **Financial Figures:** Use `stats-lg` for primary balances. All currency symbols should be slightly thinner in weight than the numerical value to maintain focus on the digits.

## Elevation & Depth
Depth is the most critical component of this design system. It is achieved through three layers:

1.  **The Canvas:** The deepest layer, a dark blue gradient (`#0F172A` to `#1E293B`).
2.  **The Glass Layer:** Semi-transparent containers with a `backdrop-filter: blur(20px)`. They feature a 1px solid border at 10% opacity white to simulate the edge of a glass pane.
3.  **The Interactive Layer:** Elements like buttons or active states use "Ambient Glows"—soft, diffused shadows that inherit the color of the element (e.g., a green glow for a "Deposit" button) rather than using standard black shadows.

Shadows should be used sparingly, primarily to lift the "Active" glass pane above others using a `0px 20px 40px rgba(0,0,0,0.4)` configuration.

## Shapes
This design system uses a **Rounded** (0.5rem base) shape language to soften the technical nature of financial data. 

- **Glass Cards:** Always use `rounded-xl` (1.5rem) to create a premium, modern feel.
- **Buttons & Inputs:** Use the standard `rounded` (0.5rem) for a crisp, functional appearance.
- **Status Indicators:** Use pill-shaped (fully rounded) containers for tags like "Cleared," "Pending," or "Flagged."

## Components
### Buttons
Primary buttons use a subtle gradient and a soft outer glow. In this design system, "positive action" buttons (Confirm/Deposit) use the Emerald Green, while "negative action" buttons (Delete/Withdraw) use the Coral Ruby.

### Glass Cards
The foundational component. Must include:
- `backdrop-filter: blur(16px)`
- `background: rgba(255, 255, 255, 0.03)`
- `border: 1px solid rgba(255, 255, 255, 0.1)`

### Input Fields
Inputs should be "Ghost" style—transparent backgrounds with only a bottom border or a very faint glass fill. On focus, the border color should glow with the Primary accent color.

### Transaction Lists
Transactions are presented in clean, horizontal rows. Each row should have a subtle hover state that increases the glass brightness from 3% to 7% opacity, giving the user immediate feedback in a dense data environment.

### Data Visualization
Charts should use "Neon" line styles—high saturation colors with a small blur effect on the line itself to make the financial trends appear as if they are glowing within the glass pane.