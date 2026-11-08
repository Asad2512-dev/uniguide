# UniGuide Pakistan — Non-Coder Administration Guide

Welcome to the **UniGuide Pakistan Centralized Administration Guide**! 

This guide was written specifically for website administrators, marketing managers, content editors, and non-programmers. You do **not** need any coding knowledge to change colors, edit text, swap logos, or update contact numbers.

Everything you need to change lives in clean configuration files and text documents.

---

## Table of Contents

1. [Where Things Live (Quick Cheatsheet)](#1-where-things-live-quick-cheatsheet)
2. [How to Change the Website Name & Slogan](#2-how-to-change-the-website-name--slogan)
3. [How to Replace the Logo and Favicon](#3-how-to-replace-the-logo-and-favicon)
4. [How to Change Brand Colors (Primary, Secondary, Backgrounds)](#4-how-to-change-brand-colors)
5. [How to Change Website Fonts](#5-how-to-change-website-fonts)
6. [How to Edit Homepage Headings, Descriptions & Buttons](#6-how-to-edit-homepage-headings-descriptions--buttons)
7. [How to Edit Navigation Menu Labels](#7-how-to-edit-navigation-menu-labels)
8. [How to Change Footer Links & Disclaimers](#8-how-to-change-footer-links--disclaimers)
9. [How to Update Contact Numbers, Emails & Office Addresses](#9-how-to-update-contact-numbers-emails--office-addresses)
10. [How to Change AI Counselor Messages & Placeholders](#10-how-to-change-ai-counselor-messages--placeholders)
11. [Publishing Your Changes](#11-publishing-your-changes)

---

## 1. Where Things Live (Quick Cheatsheet)

| What You Want to Change | File Path |
| :--- | :--- |
| **Website Name, Tagline, Phone, Email, Social Links** | `config/brand.php` |
| **Colors (Primary, Buttons, Cards, Borders)** | `resources/css/tokens.css` |
| **Fonts (Headings, Body, Code)** | `resources/css/typography.css` |
| **Homepage Headings, Stats, Hero** | `resources/content/homepage.json` |
| **Navigation Menu Names** | `resources/content/navigation.json` |
| **Header, Footer & Global Brand Text** | `resources/content/global.json` |
| **Search Placeholders, Alerts, Popups** | `resources/content/messages.json` |
| **Google Search Titles & Descriptions (SEO)** | `resources/content/seo.json` |
| **About Page Story & Statistics** | `resources/content/about.json` |
| **How It Works Steps** | `resources/content/how-it-works.json` |
| **Platform Features** | `resources/content/features.json` |
| **Contact Helpdesk & Office Locations** | `resources/content/contact.json` |
| **AI Counselor Prompts & Disclaimers** | `resources/content/ai-counselor.json` |
| **Logos & Icons** | `public/images/brand/` |

---

## 2. How to Change the Website Name & Slogan

**File path:** `config/brand.php`

Open `config/brand.php` in any text editor (VS Code, Notepad, TextEdit). You will see:

```php
'name' => 'UniGuide Pakistan',
'short_name' => 'UniGuide',
'tagline' => 'National Higher Education Discovery & Admissions Gateway',
'subtitle' => 'National Higher Education Portal',
```

### Example: Changing the Website Name
Change `'UniGuide Pakistan'` to your new name:

```php
'name' => 'UniGuide National Portal',
'short_name' => 'UniGuide',
```

Save the file. The new name will immediately update across page titles, footers, and emails.

---

## 3. How to Replace the Logo and Favicon

All official logos and browser bookmark icons (favicons) are stored in two folders:
1. `public/images/brand/`
2. `public/` (for browser root favicons)

### Official Asset Files

| Asset Type | File Name | Recommended Format | Size |
| :--- | :--- | :--- | :--- |
| Primary Logo | `public/images/brand/logo.svg` | SVG (Vector) | 40x40 px or scalable vector |
| Wordmark | `public/images/brand/wordmark.svg` | SVG (Vector) | 180x40 px |
| Browser Favicon | `public/favicon.svg` | SVG | 32x32 px vector |
| Standard Favicon | `public/favicon.ico` | ICO | 16x16 and 32x32 multi-size |
| Apple Touch Icon | `public/apple-touch-icon.png` | PNG | 180x180 px |

### How to Replace:
1. Prepare your new logo in SVG or high-resolution PNG format.
2. Name the file exactly as listed above (for example, `logo.svg` or `favicon.svg`).
3. Replace the old file in `public/images/brand/` and `public/`.
4. Refresh your browser (press `Ctrl + Shift + R` or `Cmd + Shift + R` to clear cache).

---

## 4. How to Change Brand Colors

**File path:** `resources/css/tokens.css`

Open `resources/css/tokens.css`. At the top of the file, you will find the color palette using standard HEX codes (e.g. `#4355E8`).

```css
:root {
  /* =========================================================================
     1. BRAND PALETTE
     ========================================================================= */
  --color-brand-50: #EEF0FF;
  --color-brand-100: #E0E3FF;
  --color-brand-500: #5568FE;
  --color-brand-600: #4355E8;  /* PRIMARY BRAND COLOR */
  --color-brand-700: #303AB2;  /* PRIMARY HOVER / ACCENT COLOR */
  --color-brand-800: #232B85;
  --color-brand-900: #1E256C;
  --color-brand-950: #11143E;
```

### Example: Changing the Primary Brand Color
To change the primary royal blue (`#4355E8`) to a new color (for example, emerald green `#0D9488`):
1. Change `--color-brand-600` to `#0D9488`.
2. Change `--color-brand-700` (hover color) to `#0F766E`.
3. Save the file and run `npm run build` in your terminal.
4. All buttons, active navigation links, tabs, and brand highlights across all 64 screens will automatically update to the new color!

---

## 5. How to Change Website Fonts

**File path:** `resources/css/typography.css`

Open `resources/css/typography.css`. You will find:

```css
:root {
  /* Font Family Definitions */
  --font-heading: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  --font-body: 'Inter', system-ui, -apple-system, sans-serif;
  --font-mono: 'JetBrains Mono', ui-monospace, monospace;
}
```

### Example: Changing the Heading Font
To change headings to `Poppins`:
1. Change `--font-heading` to:
   ```css
   --font-heading: 'Poppins', system-ui, -apple-system, sans-serif;
   ```
2. Save the file.

---

## 6. How to Edit Homepage Headings, Descriptions & Buttons

**File path:** `resources/content/homepage.json`

Open `resources/content/homepage.json`. This file holds the editable text of the homepage:

```json
{
  "hero": {
    "eyebrow": "Fall 2026 National University Admissions Cycle Live",
    "institutions_listed": ":count Listed Institutions",
    "headline_main": "Find your university in Punjab or Islamabad.",
    "headline_gradient": "Calculate exact merit.",
    "headline_end": "Apply with confidence.",
    "subtitle": "Discover universities and admission opportunities across Punjab and Islamabad."
  }
}
```

### How to Edit:
Simply change the text inside the quotation marks:
```json
"headline_main": "Discover top higher education in Pakistan.",
```
> **Warning**: Do not remove the quotes `" ... "` or the commas `,` at the end of lines. See [docs/CONTENT_EDITING_GUIDE.md](file:///Applications/MAMP/htdocs/uniguide/docs/CONTENT_EDITING_GUIDE.md) for JSON safety tips.

---

## 7. How to Edit Navigation Menu Labels

**File path:** `resources/content/navigation.json`

Open `resources/content/navigation.json`. You can rename any link that appears in the top menu, sidebar, or mobile drawer:

```json
{
  "public": {
    "counselor": {
      "label": "AI Counselor",
      "badge": "Intelligent"
    },
    "universities": {
      "label": "Explore Universities"
    },
    "programs": {
      "label": "Degree Programs"
    }
  }
}
```

### Example: Renaming "Explore Universities" to "All Universities"
Change:
```json
"universities": {
  "label": "All Universities"
}
```
Save the file. The top menu will now display **All Universities** without altering any links.

---

## 8. How to Change Footer Links & Disclaimers

**File path:** `resources/content/global.json`

Open `resources/content/global.json`. Under the `"footer"` section:

```json
"footer": {
  "institutions_programs_title": "Institutions & Programs",
  "student_guidance_title": "Student Guidance",
  "platform_charters_title": "Platform & Charters",
  "data_transparency": "Data Transparency",
  "institutional_inquiries": "Institutional Inquiries"
}
```

And under `"brand"`:
```json
"copyright": "© :year UniGuide Pakistan. All rights reserved. Data verified against official provincial charters and gazetted fee schedules."
```
`:year` is automatically replaced with the current calendar year.

---

## 9. How to Update Contact Numbers, Emails & Office Addresses

**File path:** `config/brand.php` and `resources/content/contact.json`

To change contact phone numbers and emails, edit `config/brand.php`:

```php
'contact' => [
    'email' => 'info@uniguide.pk',
    'support_email' => 'support@uniguide.pk',
    'phone' => '+92 (051) 9040-2026',
    'hours' => 'Mon – Fri: 9:00 AM – 5:00 PM PKT',
    'desks' => [
        'ict' => [
            'region' => 'Federal & ICT Headquarters',
            'address' => 'Higher Education Complex, Sector H-9/1, Islamabad',
            'phone' => '+92 (051) 9040-2026',
        ],
        'punjab' => [
            'region' => 'Punjab Regional Coordination',
            'address' => 'Mall Road Academic Secretariat, Lahore',
            'phone' => '+92 (042) 3578-2026',
        ],
    ],
],
```

---

## 10. How to Change AI Counselor Messages & Placeholders

**File path:** `resources/content/ai-counselor.json`

Open `resources/content/ai-counselor.json`:

```json
{
  "header": {
    "badge": "UniGuide National AI Admission Counselor",
    "calibration": "Fall 2026 Calibrated",
    "title": "Intelligent admission guidance,",
    "title_gradient": "grounded in verified statutory data.",
    "description": "Ask anything about Pakistani university merit formulas, closing cutoffs, tuition fees, entry-test weightages, and fully-funded scholarships."
  },
  "composer": {
    "placeholder": "Ask about university cutoffs, merit calculation, fees, or scholarships...",
    "submit_button": "Consult Counselor",
    "disclaimer": "Counselor guidance is synthesized from gazetted institutional documents and official merit lists. Always verify final admission notices."
  }
}
```

You can customize the search placeholder, title, and legal disclaimer without modifying any AI backend code.

---

## 11. Publishing Your Changes

When you make changes to:
- **Text files (`resources/content/*.json`)**: Your changes take effect **instantly**. Simply refresh your browser!
- **CSS files (`resources/css/tokens.css` or `typography.css`)**: Run the frontend compiler command in your terminal:
  ```bash
  npm run build
  ```
- **Brand config (`config/brand.php`)**: If your production server uses configuration caching, refresh the cache:
  ```bash
  php artisan config:clear
  ```

