# UniGuide Pakistan

UniGuide helps students find universities, compare programs, check eligibility, explore scholarships, and track admissions. It includes a counselor workspace and separate student, university administrator, and national administrator areas. The current regional catalog focuses on Punjab and Islamabad Capital Territory.

## Start here

For everyday text, color, and branding changes, read the [non-coder guide](docs/NON_CODER_GUIDE.md). For text-file examples, read the [content editing guide](docs/CONTENT_EDITING_GUIDE.md).

## How the code fits together

A visit to a page follows this path:

1. `routes/web.php` matches the page address to a controller method.
2. `app/Http/Controllers/` receives the request and chooses what to show.
3. `app/Services/` handles tasks such as finding programs or calculating eligibility.
4. `app/Models/` reads and writes database records.
5. `resources/views/pages/` turns the results into a page using shared components from `resources/views/components/`.

For example, `/universities` uses `UniversityController::index()`, which asks `DiscoveryService` for directory data and renders `resources/views/pages/discovery/universities/index.blade.php`.

| Folder or file | Purpose |
| --- | --- |
| `resources/content/` | Editable text used by calls to the `content()` helper |
| `config/brand.php` | Brand settings used by calls to `config('brand.…')` |
| `config/navigation.php` | Menu destinations, grouping, and access settings |
| `config/uniguide.php` | Regional coverage settings |
| `resources/css/` | Colors, fonts, and page styling |
| `resources/js/` | Browser interactions, counselor chat, mobile layout, and charts |
| `public/` | Public images, icons, and the application entry point |
| `database/migrations/` | Definitions of database tables and columns |
| `database/seeders/` | Initial records and sample data; review before running |
| `app/Policies/` and `app/Http/Middleware/` | Access checks |
| `app/Jobs/` | Background tasks |
| `tests/` | Automated checks |
| `.env` | Private settings for this installation; keep out of version control |

Some pages and services still contain sample records and text directly in code. A JSON edit changes only the places that read that JSON key. See the editing guides before changing a record or promising a live integration.

## Run locally

You need PHP compatible with `composer.json` (the project is currently developed on PHP 8.5), Composer, and Node.js compatible with the installed Vite version.

For a new local installation:

```bash
composer run setup
composer run dev
```

The setup command installs dependencies, creates `.env` if missing, generates an application key, runs database migrations, and builds browser assets. Run it against a new local database. For an existing installation, preserve its `.env` and application key; install dependencies with `composer install` and `npm install --ignore-scripts` instead.

The default example configuration uses SQLite. If using MySQL through MAMP, set the database connection, host, port, database name, and credentials in `.env` before migrating. Use the address printed by the local development server.

Database migrations create tables. Seeders add records separately; they include sample data and should be reviewed before use on a shared database.

## Demo logins

All three demo accounts use password `12345678`. The sign-in page includes buttons to fill these credentials.

| Role | Email |
| --- | --- |
| Student | `student@gmail.com` |
| NUML Multan university administrator | `numl@gmail.com` |
| UniGuide platform administrator | `admin@gmail.com` |

The platform administrator is a UniGuide operator. Listing approval does not grant accreditation.
The NUML account belongs to the existing NUML institution record, which includes its Multan campus.

Run `php artisan migrate` and `php artisan db:seed --class=UserSeeder` to install the new table and demo accounts. On a new database, run the catalog seeders first.

Universities can request a listing at `/universities/join`. Platform administrators review persisted submissions at `/admin/national/applications`; approval adds the institution to the public directory. Administrator accounts are provisioned separately.

## Check changes

```bash
php artisan test --compact
node --test tests/Unit/*.test.mjs
npm run build
```

The PHP suite is configured to use `database/database.sqlite` with the testing environment. Keep this separate from valuable application data.

After editing PHP, format changed files with:

```bash
vendor/bin/pint --dirty
```

Do not edit `vendor/`, `node_modules/`, or `public/build/` directly. Their contents are installed or generated.

## Database-backed operation

Public catalogs and all three account workspaces read persisted records. Empty collections stay empty; there are no sample-profile or session-data fallbacks. The only seeded accounts are `numl@gmail.com`, `student@gmail.com`, and `admin@gmail.com`, each using `12345678` for local use. Registration can create new student accounts.

Run `php artisan migrate` before starting the app. `php artisan db:seed` imports the source-backed university snapshots and the three accounts; retired sample seeders no longer generate data.

`php artisan uniguide:retire-demo-data` privately backs up existing tables, disables extra accounts, and retires specifically identified old example records. Private backups are stored under the local storage disk's `backups/` directory. This cleanup has been applied to the current local database; existing registered-user data is preserved, with extra accounts inactive.

Saved items, academic profiles, test results, notifications, admission tracking, institution data, settings, document metadata, and reviews persist in the database. Uploaded files are kept on private local storage with account-scoped download routes. Uploaded results are self-reported until reviewed.

In-app reminders publish on the user's next visit; `php artisan schedule:work` also publishes due reminders in the background. There is no connected email delivery, external AI provider, embedding service, payment gateway, or live crawler. Those capabilities do not report fabricated success. Extraction review decisions are recorded; arbitrary extracted strings do not automatically overwrite catalog fields. Use the corresponding institution editor to publish checked changes.
