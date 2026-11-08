# UniGuide brand reference

The product name is **UniGuide Pakistan**, shortened to **UniGuide**. The current catalog covers Punjab and Islamabad Capital Territory.

## Source files

| Setting | File |
| --- | --- |
| Brand settings, contact details, metadata, and asset paths | [`config/brand.php`](../config/brand.php) |
| Color palette and shared design values | [`resources/css/tokens.css`](../resources/css/tokens.css) |
| Font families, sizes, and weights | [`resources/css/typography.css`](../resources/css/typography.css) |
| Style imports and utility mappings | [`resources/css/app.css`](../resources/css/app.css) |
| On-page logo and wordmark artwork | [`resources/views/components/brand/`](../resources/views/components/brand/) |
| Standalone brand images | [`public/images/brand/`](../public/images/brand/) |
| Browser and installed-app icons | `public/favicon.*`, `public/favicon-*.png`, `public/apple-touch-icon.png`, and `public/site.webmanifest` |

## Main colors

| Token | Color | Typical use |
| --- | --- | --- |
| `--color-brand-50` | `#EEF0FF` | Pale brand backgrounds |
| `--color-brand-500` | `#5C67F5` | Bright brand accents |
| `--color-brand-600` | `#4355E8` | Primary buttons and links |
| `--color-brand-700` | `#3542CE` | Darker brand accents and hover states |
| `--color-brand-900` | `#262E8A` | Dark brand backgrounds |

Use the existing surface, ink, border, success, warning, and error tokens for other interface colors. Check text contrast in its actual background and state when changing colors.

## Fonts

| Token | Font | Use |
| --- | --- | --- |
| `--font-heading` | Plus Jakarta Sans | Headings |
| `--font-sans` | Inter | Body text and controls |
| `--font-mono` | JetBrains Mono | Code-style labels |

Fonts are loaded in the base layouts. A font change may require updating both the typography tokens and those font-loading links.

## Updating the brand

Some templates include their own brand text, inline SVG, or fixed colors. Changes to the shared configuration or image files affect only the places that use them. Search for the old value and inspect the relevant pages when renaming the product or replacing the artwork.

Follow the [non-coder editing guide](NON_CODER_GUIDE.md) for editing and publishing steps.
