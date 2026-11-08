# UniGuide editing guide for non-coders

You can make many routine changes by editing text and style files. You do not need to understand the whole application to change a heading. Changes to database structure, permissions, calculations, or integrations need development work.

## Find the right file

| What you want to change | Start here |
| --- | --- |
| Homepage headings and introductory text | `resources/content/homepage.json` |
| Menu wording | `resources/content/navigation.json` |
| Shared text and footer labels | `resources/content/global.json` |
| About, features, how-it-works, or contact headings | The matching JSON file in `resources/content/` |
| Counselor headings and help text | `resources/content/ai-counselor.json` |
| Other page labels | See the [content editing guide](CONTENT_EDITING_GUIDE.md) |
| Brand settings | `config/brand.php` |
| Main colors | `resources/css/tokens.css` |
| Fonts and text sizes | `resources/css/typography.css` |
| Browser icons | `public/favicon.*`, `public/favicon-*.png`, and `public/apple-touch-icon.png` |
| Logo and wordmark shown inside pages | `resources/views/components/brand/` |
| University, campus, fee, and admission records | The appropriate administration area; some screens still use sample data in services |

These are starting points, not a guarantee that every page reads the same file. Some text, contacts, brand names, and logos are written directly in page templates or controller data. If an edit has no effect, search the project for the exact words shown on the page.

## Make one small change

1. Save a backup of the file outside the project, or start from a clean Git working copy.
2. Open the file in a plain-text editor.
3. Change one value. Keep surrounding quotation marks, commas, and brackets.
4. Save, then follow the publishing instructions below.
5. Check the affected page on a narrow and a wide screen. Check its links and buttons too.

For a homepage heading, find this entry in `resources/content/homepage.json`:

```json
"headline_main": "Find your university in Punjab or Islamabad."
```

Change only the words inside the quotation marks on the right. Keep the key `headline_main` unchanged. The example is a single entry; keep any comma required by the surrounding file.

Read the [content editing guide](CONTENT_EDITING_GUIDE.md) for complete JSON examples and placeholders such as `:count`.

## Brand name and contact details

`config/brand.php` uses PHP entries like:

```php
'name' => 'UniGuide Pakistan',
'short_name' => 'UniGuide',
```

Edit the text on the right, leaving the keys and punctuation in place. For an apostrophe inside a single-quoted PHP value, use `\'`.

These settings affect only the templates that read them. For example, the contact page also contains text directly in its template, and the wordmark includes its own artwork. Check each relevant page after a brand or contact change.

## Colors and fonts

In `resources/css/tokens.css`, `--color-brand-600` is the main brand color and `--color-brand-700` is a darker shade used for hover states and accents. Change related shades together and check that text remains readable. Artwork and colors written directly into templates may need separate edits.

In `resources/css/typography.css`, `--font-heading` controls heading fonts, `--font-sans` controls body text, and `--font-mono` controls code-style text. A new font must also be loaded by the page layouts; changing its name alone does not download it.

Build the assets after changing styles:

```bash
npm run build
```

## Logos and browser icons

The logo and wordmark components contain inline SVG artwork. Replacing `public/images/brand/logo.svg` alone does not replace that artwork. Have a developer update the components when changing the on-page logo.

Browser icon paths are set in `config/brand.php`. Replace the corresponding files in `public/`, keeping their formats and dimensions. Check `public/site.webmanifest` when changing the installed-app name or icons. Refresh the browser cache to see updated icons.

## Publish and check

Run commands from the project folder.

| Changed file | Next step |
| --- | --- |
| `resources/content/*.json` | Validate the JSON, then refresh the page; deploy the file if editing locally |
| `config/*.php` | Refresh the configuration cache using your deployment process; locally, run `php artisan config:clear` |
| CSS or JavaScript | Run `npm run build`, or use the running development server |
| Blade page templates | Refresh the page; if a compiled view is stale, run `php artisan view:clear` |
| Public images | Refresh the browser cache |

Editing a local file does not publish it to a hosted website. Follow the site's deployment process. Long-running application servers may also need to reload to pick up changes.

If something breaks, undo your last edit or restore the one file from your backup. Avoid restoring a whole folder when it contains other changes you want to keep.
