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
        // Static region choices are cheap; catalog queries belong only to views that render them.
        View::composer('pages.auth.register', function ($view): void {
            $view->with('coverageRegions', app(RegionCatalog::class)->regions());
        });
        View::composer(['pages.marketing.home', 'pages.marketing.about', 'pages.student.saved-universities'], function ($view): void {
            $view->with('coverageCounts', ['universities' => University::count(), 'campuses' => Campus::count(), 'programs' => Program::count()]);
        });
        View::composer('pages.student.tracker', function ($view): void {
            $view->with('coveragePrograms', Program::select(['id', 'name', 'university_id', 'campus_id'])->with(['university:id,name', 'campus:id,name'])->orderBy('name')->get());
        });
        Blade::anonymousComponentPath(resource_path('views/layouts'), 'layouts');
    }
}
