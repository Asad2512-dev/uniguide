<?php

use App\Support\Content\ContentRepository;

if (! function_exists('content')) {
    /**
     * Retrieve centralized content from resources/content/*.json.
     *
     * @param  string|null  $key  Dot-notated key e.g. 'homepage.hero.title'
     * @param  mixed  $default  Fallback value if key is not found
     * @param  array  $replace  Key-value replacements for placeholders (:key or {key})
     * @return mixed|ContentRepository
     */
    function content(?string $key = null, mixed $default = null, array $replace = []): mixed
    {
        $repo = app(ContentRepository::class);

        if ($key === null) {
            return $repo;
        }

        // If the second parameter is an array and third is empty, treat second as replacements
        if (is_array($default) && empty($replace)) {
            $replace = $default;
            $default = null;
        }

        return $repo->get($key, $default, $replace);
    }
}
