# UniGuide Pakistan — Brand & Design Token Guide

This document defines the visual system, color taxonomy, typography scale, asset specifications, and component tokens of **UniGuide Pakistan**.

All tokens are defined as CSS custom properties in [`resources/css/tokens.css`](file:///Applications/MAMP/htdocs/uniguide/resources/css/tokens.css) and [`resources/css/typography.css`](file:///Applications/MAMP/htdocs/uniguide/resources/css/typography.css).

---

## 1. Brand Identity & Purpose

- **Product Name:** UniGuide Pakistan
- **Short Name:** UniGuide
- **Acronym:** UG
- **Mission:** Eliminating higher education information asymmetry across Pakistan by providing verified, statutory admissions intelligence, merit aggregate calculation, and degree program comparison.
- **Coverage Focus:** Punjab and Islamabad Capital Territory (ICT), expanding nationally across 248+ chartered institutions.

---

## 2. Color Palette & Taxonomy

The UniGuide palette is built around an authoritative, trustworthy deep indigo/royal blue, complemented by crisp neutral surfaces, lavender accents, and high-contrast ink scales for WCAG 2.2 AA accessibility.

### Primary Brand Palette (Royal / Deep Indigo)

| Token | HEX Value | Role / Usage |
| :--- | :--- | :--- |
| `--color-brand-50` | `#EEF0FF` | Active navigation pill backgrounds, badge fills |
| `--color-brand-100` | `#E0E3FF` | Light badge borders, subtle card highlights |
| `--color-brand-200` | `#C7CDFE` | Input focus rings, card borders |
| `--color-brand-300` | `#A5B0FD` | Secondary interactive accents |
| `--color-brand-400` | `#7E87F9` | Gradient focal stop, icon accents |
| `--color-brand-500` | `#5568FE` | Active state indicators, pulse rings |
| **`--color-brand-600`** | **`#4355E8`** | **Primary Brand Color**: Primary buttons, key logos, active tabs |
| **`--color-brand-700`** | **`#303AB2`** | **Primary Hover**: Button hover, darker text links |
| `--color-brand-800` | `#232B85` | High-contrast headers, active button presses |
| `--color-brand-900` | `#1E256C` | Deep container backgrounds, shield base |
| `--color-brand-950` | `#11143E` | Midnight text headers, dark modal headers |

### Surfaces & Backgrounds

| Token | HEX Value | Role / Usage |
| :--- | :--- | :--- |
| `--color-canvas` | `#FAFAFA` | Page body background (clean off-white) |
| `--color-surface` | `#FFFFFF` | Card surface, modal surface, header background |
| `--color-surface-subtle` | `#F5F5F7` | Table header background, alternating rows, search bars |
| `--color-surface-elevated` | `#FFFFFF` | Flyouts, dropdown menus, command palette |

### Ink & Typography Hierarchy

| Token | HEX Value | Role / Usage | Contrast Ratio |
| :--- | :--- | :--- | :--- |
| `--color-ink-950` | `#0A0D14` | Primary Headings (H1, H2, H3), bold titles | 18.2:1 (AAA) |
| `--color-ink-900` | `#1B1E28` | Body text, card titles, table data | 15.1:1 (AAA) |
| `--color-ink-700` | `#333748` | Secondary body text, form labels | 10.4:1 (AAA) |
| `--color-ink-600` | `#4B5166` | Paragraph descriptions, subtitles | 7.2:1 (AAA) |
| `--color-ink-500` | `#666D82` | Metadata labels, breadcrumb text | 4.8:1 (AA) |
| `--color-ink-400` | `#9198AC` | Placeholder text, disabled icons | 2.9:1 |

### Borders

| Token | HEX Value | Role / Usage |
| :--- | :--- | :--- |
| `--color-border-subtle` | `#F0F1F5` | Divider lines between list items |
| `--color-border-default` | `#E4E6ED` | Card borders, table borders, input borders |
| `--color-border-strong` | `#C8CCD8` | Focused element borders, prominent dividers |

### Semantic Functional States

| State | Primary Hex | Background Hex | Border Hex | Usage |
| :--- | :--- | :--- | :--- | :--- |
| **Success** | `#16A34A` (`--color-success-600`) | `#F0FDF4` | `#BBF7D0` | Eligible badges, open admissions, confirmed challans |
| **Warning** | `#D97706` (`--color-warning-600`) | `#FFFBEB` | `#FDE68A` | Closing soon alerts, pending verifications, review flags |
| **Danger** | `#DC2626` (`--color-error-600`) | `#FEF2F2` | `#FECACA` | Ineligible alerts, missed deadlines, delete dialogs |
| **Info** | `#2563EB` (`--color-info-600`) | `#EFF6FF` | `#BFDBFE` | System announcements, instructions, notice cards |

---

## 3. Typography Scale & Specifications

Defined in [`resources/css/typography.css`](file:///Applications/MAMP/htdocs/uniguide/resources/css/typography.css).

### Font Families
- **Headings & Display:** `Plus Jakarta Sans`, system-ui, -apple-system, sans-serif
  - Weights: `600` (SemiBold), `700` (Bold), `800` (ExtraBold)
  - Letter-spacing: `-0.025em` (tight) to `-0.015em`
- **Body & Controls:** `Inter`, system-ui, -apple-system, sans-serif
  - Weights: `400` (Regular), `500` (Medium), `600` (SemiBold)
  - Line-height: `1.5` to `1.625`
- **Numbers, Aggregates & Code:** `JetBrains Mono`, ui-monospace, monospace
  - Weights: `500` (Medium), `700` (Bold)
  - Usage: Percentages (`88.42%`), roll numbers, challan reference IDs

---

## 4. Brand Assets & Dimensions

Official vector brand assets are stored in `public/images/brand/` and `resources/images/brand/`.

| Asset | File Name | Format | Dimensions | Description |
| :--- | :--- | :--- | :--- | :--- |
| **Primary Logo** | `logo.svg` | SVG | 40x40 px (viewBox 0 0 40 40) | Shield foundation with architectural portal and national diamond |
| **Logo Mark** | `logo-mark.svg` | SVG | 40x40 px | Standalone icon without text |
| **Wordmark** | `wordmark.svg` | SVG | 180x40 px | Lockup of logo mark + "UniGuide" typography |
| **Favicon SVG** | `favicon.svg` | SVG | 32x32 px | Modern browser vector favicon |
| **Favicon ICO** | `favicon.ico` | ICO | 16x16, 32x32 px | Legacy browser compatibility icon |
| **Apple Touch Icon** | `apple-touch-icon.png` | PNG | 180x180 px | iOS Home Screen bookmark icon |

### Logo Clearspace Rules
- Always maintain clearspace equal to at least half the height of the logo mark (`0.5h`) on all sides.
- Do not stretch, skew, or rotate the logo shield.
- Do not place the colored logo over low-contrast colored backgrounds. On dark backgrounds, use the inverted white wordmark variant.

---

## 5. Component Tokens

CSS custom properties in `tokens.css` configure components universally:

```css
:root {
  /* Radii */
  --radius-button: 0.75rem;    /* 12px rounded buttons */
  --radius-card: 1rem;         /* 16px rounded cards */
  --radius-input: 0.75rem;     /* 12px rounded inputs */
  --radius-modal: 1.5rem;      /* 24px rounded dialogs */

  /* Shadows */
  --shadow-card: 0 1px 3px 0 rgb(0 0 0 / 0.05), 0 1px 2px -1px rgb(0 0 0 / 0.05);
  --shadow-elevated: 0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.04);
  --shadow-modal: 0 25px 50px -12px rgb(0 0 0 / 0.25);

  /* Geometry */
  --width-landing-container: 80%;
  --max-width-landing: 80rem;
}
```

