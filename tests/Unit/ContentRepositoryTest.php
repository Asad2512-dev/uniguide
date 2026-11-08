<?php

namespace Tests\Unit;

use App\Support\Content\ContentRepository;
use Tests\TestCase;

class ContentRepositoryTest extends TestCase
{
    /**
     * Test repository retrieves nested dot-notated values from JSON files.
     */
    public function test_retrieves_nested_content_keys(): void
    {
        $repo = app(ContentRepository::class);

        $brandName = $repo->get('global.brand.name');
        $this->assertEquals('UniGuide Pakistan', $brandName);

        $headline = $repo->get('homepage.hero.headline_main');
        $this->assertEquals('Find your university in Punjab or Islamabad.', $headline);
    }

    /**
     * Test global content() helper function works identically.
     */
    public function test_global_helper_resolves_content(): void
    {
        $this->assertEquals('UniGuide Pakistan', content('global.brand.name'));
        $this->assertEquals('Confirm Deletion', content('messages.dialogs.delete_title'));
    }

    /**
     * Test fallback defaults when a key does not exist.
     */
    public function test_returns_fallback_default_on_missing_key(): void
    {
        $this->assertEquals('Fallback Text', content('global.non_existent_key', 'Fallback Text'));
        $this->assertEquals('Default Value', content('unknown_file.some_key', 'Default Value'));
    }

    /**
     * Test interpolation with placeholders (:key and {key}).
     */
    public function test_interpolates_placeholders(): void
    {
        $result = content('homepage.hero.institutions_listed', null, ['count' => 248]);
        $this->assertEquals('248 Listed Institutions', $result);

        // Also test shorthand when passing replacement array as second argument
        $shorthand = content('homepage.hero.institutions_listed', ['count' => 300]);
        $this->assertEquals('300 Listed Institutions', $shorthand);
    }

    /**
     * Test path traversal or unauthorized files are blocked.
     */
    public function test_blocks_arbitrary_file_access(): void
    {
        $repo = app(ContentRepository::class);

        $this->assertEmpty($repo->all('../../../etc/passwd'));
        $this->assertNull($repo->get('../../../etc/passwd.root'));
    }

    /**
     * Test centralized brand configuration is accessible and structured.
     */
    public function test_brand_config_returns_accurate_metadata(): void
    {
        $this->assertEquals('UniGuide Pakistan', config('brand.name'));
        $this->assertEquals('UniGuide', config('brand.short_name'));
        $this->assertEquals('Pakistan', config('brand.country'));
        $this->assertEquals('#4355E8', config('brand.seo.theme_color'));
        $this->assertNotEmpty(config('brand.assets.logo'));
        $this->assertNotEmpty(config('brand.contact.email'));
    }
}
