<?php

namespace App\Services\SeedData;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class PlatformSnapshot
{
    public const ACCOUNTS = ['numl@gmail.com', 'student@gmail.com', 'admin@gmail.com'];

    // Dependency order also makes the export portable between MySQL and SQLite.
    public const TABLES = [
        'users', 'provinces', 'cities', 'universities', 'campuses', 'departments',
        'programs', 'admissions', 'scholarships', 'scholarship_regions', 'eligibility_rules',
        'data_sources', 'university_source_records', 'scraping_jobs', 'ai_extractions',
        'verification_requests', 'charter_approvals', 'knowledge_base_documents',
        'knowledge_base_chunks', 'student_profiles', 'student_applications', 'saved_items',
        'inquiries', 'notifications', 'university_applications', 'ai_conversations',
        'ai_messages', 'audit_logs', 'system_settings',
    ];

    public function export(): array
    {
        $tables = [];
        foreach (self::TABLES as $table) {
            $columns = collect(Schema::getColumns($table))->keyBy('name');
            $query = DB::table($table);
            if ($columns->has('deleted_at')) {
                $query->whereNull('deleted_at');
            }
            if ($table === 'users') {
                $query->whereIn('email', self::ACCOUNTS);
            }
            $rows = $query->get()->map(fn ($row) => (array) $row)->all();
            if (in_array($table, ['student_profiles', 'student_applications', 'saved_items', 'notifications', 'ai_conversations'], true)) {
                $allowedUsers = array_column($tables['users']['rows'], 'id');
                $rows = array_values(array_filter($rows, fn ($row) => in_array($row['user_id'], $allowedUsers)));
            }
            $foreignKeys = Schema::getForeignKeys($table);
            $keys = collect(Schema::getIndexes($table))->filter(fn ($index) => $index['unique'] && ! $index['primary'])->pluck('columns')->all();
            foreach ($rows as &$row) {
                if ($table === 'users') {
                    unset($row['password'], $row['remember_token']);
                }
                foreach ($foreignKeys as $foreign) {
                    $parent = $foreign['foreign_table'];
                    if (! isset($tables[$parent])) {
                        continue;
                    }
                    $column = $foreign['columns'][0];
                    $parentColumn = $foreign['foreign_columns'][0];
                    if ($row[$column] !== null && ! in_array($row[$column], array_column($tables[$parent]['rows'], $parentColumn))) {
                        if ($columns[$column]['nullable']) {
                            $row[$column] = null;
                        } else {
                            $row = null;
                            break;
                        }
                    }
                }
            }
            unset($row);
            $tables[$table] = ['unique_keys' => $keys, 'foreign_keys' => $foreignKeys, 'rows' => array_values(array_filter($rows, fn ($row) => $row !== null))];
        }

        return ['version' => 1, 'exported_at' => now()->toIso8601String(), 'tables' => $tables];
    }

    public function restore(string $path): void
    {
        $snapshot = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        if (($snapshot['version'] ?? null) !== 1 || array_keys($snapshot['tables'] ?? []) !== self::TABLES) {
            throw new RuntimeException('Unsupported or incomplete platform seed snapshot.');
        }
        DB::transaction(function () use ($snapshot) {
            $ids = [];
            foreach ($snapshot['tables'] as $table => $data) {
                foreach ($data['rows'] as $row) {
                    $sourceId = $row['id'] ?? null;
                    foreach ($data['foreign_keys'] as $foreign) {
                        $column = $foreign['columns'][0];
                        $parent = $foreign['foreign_table'];
                        if ($row[$column] !== null && isset($ids[$parent][$row[$column]])) {
                            $row[$column] = $ids[$parent][$row[$column]];
                        }
                    }
                    if ($table === 'users') {
                        if (! in_array($row['email'], self::ACCOUNTS, true)) {
                            throw new RuntimeException('The seed snapshot may contain only the three configured accounts.');
                        }
                        $row['password'] = Hash::make('12345678');
                        $row['remember_token'] = null;
                    }
                    if ($table === 'system_settings') {
                        $row['key'] = preg_replace_callback('/^(university\.)(\d+)(\.)(.*)$/', function ($match) use ($ids) {
                            $suffix = preg_replace_callback('/^(campus|department|program|scholarship)\.(\d+)/', function ($part) use ($ids) {
                                $parent = ['campus' => 'campuses', 'department' => 'departments', 'program' => 'programs', 'scholarship' => 'scholarships'][$part[1]];

                                return $part[1].'.'.($ids[$parent][$part[2]] ?? $part[2]);
                            }, $match[4]);

                            return $match[1].($ids['universities'][$match[2]] ?? $match[2]).$match[3].$suffix;
                        }, $row['key']);
                    }
                    $identity = null;
                    foreach ($data['unique_keys'] as $key) {
                        if (count(array_filter($key, fn ($column) => ! isset($row[$column]))) === 0) {
                            $identity = array_intersect_key($row, array_flip($key));
                            break;
                        }
                    }
                    if ($sourceId === null) {
                        $identity ??= $row; // Pivot tables have composite identities.
                    } else {
                        $identity ??= ['id' => $sourceId];
                    }
                    $existing = DB::table($table)->where($identity)->first();
                    if ($existing) {
                        // Repeated seeding must not reset edits made through the application.
                        $destinationId = $existing->id ?? null;
                    } else {
                        if ($sourceId !== null && is_numeric($sourceId)) {
                            if (DB::table($table)->where('id', $sourceId)->exists()) {
                                unset($row['id']);
                            }
                            $destinationId = DB::table($table)->insertGetId($row);
                        } else {
                            DB::table($table)->insert($row);
                            $destinationId = $sourceId;
                        }
                    }
                    if ($sourceId !== null) {
                        $ids[$table][$sourceId] = $destinationId;
                    }
                }
            }
        });
    }
}
