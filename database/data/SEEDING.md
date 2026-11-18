The default seeder restores `platform-seed.json`, a snapshot of the current local database exported on 18 November 2026. It includes the current catalog, source provenance, settings, and only these accounts:

| Email | Role | Password |
| --- | --- | --- |
| numl@gmail.com | University administrator (NUML) | 12345678 |
| student@gmail.com | Student | 12345678 |
| admin@gmail.com | Platform administrator | 12345678 |

Run `php artisan migrate --seed` on a new installation. Repeated seeding preserves existing records and application edits. It adds missing snapshot records; it does not delete extra records or reset existing passwords. Numeric relationships are remapped when existing records have different IDs. Programs, admissions, scholarships, and student activity are empty in this snapshot because there are no active records for them in the source database.

To capture a later database state, run `php artisan uniguide:export-seed-snapshot` against that database and review the JSON before sharing it. Password hashes, login sessions, reset tokens, cache, queued jobs, soft-deleted records, and retired accounts' private profiles/conversations are excluded. Uploaded files are not embedded in JSON; retain private storage separately if a future snapshot contains uploaded documents.

The snapshot does not overwrite the live database during export. Do not run `migrate:fresh` against a database you want to keep.
