# Editing website text

Many page labels and headings are stored in `resources/content/`. These files use JSON, a text format that pairs a name (the key) with a value. Templates read them through the `content()` helper.

Not every visible sentence is stored here. Some pages use text directly in Blade templates, controllers, or services. Editing JSON does not create database records or enable a feature described by the text.

## Choose a file

| File | Text it groups |
| --- | --- |
| `global.json` | Shared brand text, footer labels, and common buttons |
| `navigation.json` | Menu labels |
| `homepage.json` | Homepage headings and calls to action |
| `about.json`, `features.json`, `how-it-works.json` | Marketing page text |
| `contact.json` | Contact page text |
| `universities.json`, `programs.json` | Directory headings and filters |
| `admissions.json`, `scholarships.json` | Admission and scholarship page text |
| `comparison.json`, `eligibility.json` | Comparison and eligibility labels |
| `ai-counselor.json` | Counselor headings and help text |
| `student.json` | Student area text |
| `university-admin.json`, `national-admin.json` | Administration area text |
| `authentication.json` | Sign-in and registration text |
| `messages.json` | Dialogs, alerts, and notification wording |
| `seo.json` | Page metadata used by the layouts |

## Edit values, keep keys

This is a complete, valid JSON example:

```json
{
  "title": "Welcome to UniGuide Pakistan",
  "button": "Explore universities"
}
```

Change `Welcome to UniGuide Pakistan` or `Explore universities`. Keep `title` and `button` unchanged. Real files contain more entries; preserve their structure.

- Put text inside straight double quotes.
- Separate entries with commas. The last entry in an object or array has no trailing comma.
- Keep all opening and closing braces and brackets.
- Do not add comments inside JSON.
- Write an internal double quote as `\"`.
- Apostrophes need no escape in JSON. `\'` is invalid JSON.

For example:

```json
{
  "description": "Pakistan's university guide",
  "welcome": "Welcome to \"UniGuide\""
}
```

## Keep placeholders

A value such as `":count Listed Institutions"` contains a placeholder. The application supplies the number. You can change the surrounding words to `":count Universities Available"`, keeping `:count` intact.

Keep other placeholders such as `:year`, `:name`, and `{name}` exactly as written. They are replaced only when the template supplies a matching value.

## Validate and preview

Make a backup before editing. To check one file without installing anything, run this command from the project folder:

```bash
php -r 'json_decode(file_get_contents("resources/content/homepage.json"), true, 512, JSON_THROW_ON_ERROR); echo "JSON is valid!\n";'
```

Replace `homepage.json` with the file you edited. An error means the JSON needs fixing before publication. Valid JSON does not guarantee the text fits the page, so also preview the affected page and its mobile layout.

If text does not change, search for the exact visible sentence in the project. The page may use a different key or text directly in a template. On a long-running application server, the content reader may retain previously loaded values until the server reloads.

## Recover a mistake

Use your editor's Undo command or restore the affected file from your backup. Review the difference before restoring a file through Git: a restore discards uncommitted changes in that file.

For colors, branding, and publishing steps, see the [non-coder guide](NON_CODER_GUIDE.md).
