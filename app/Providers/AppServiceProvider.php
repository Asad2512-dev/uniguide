<?php

namespace App\Providers;

use App\Models\Campus;
use App\Models\Program;
use App\Models\University;
use App\Services\RegionCatalog;
use App\Support\Content\ContentRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Support/helpers.php');

        $this->app->singleton(ContentRepository::class, function () {
            return new ContentRepository;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('counselor', fn (Request $request) => Limit::perMinute(20)->by($request->user()?->id ? 'user:'.$request->user()->id : 'ip:'.$request->ip()));
        View::composer('pages.*', function ($view): void {
            $catalog = app(RegionCatalog::class);
            $view->with('coverageRegions', $catalog->regions());
            $view->with('coverageCities', $catalog->cities());
            $view->with('coveragePrograms', Program::with(['university', 'campus'])->get());
            $view->with('coverageCounts', ['universities' => University::count(), 'campuses' => Campus::count(), 'programs' => Program::count()]);
        });
        Blade::anonymousComponentPath(resource_path('views/layouts'), 'layouts');
    }
}
