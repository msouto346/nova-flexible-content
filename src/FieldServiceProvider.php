<?php

namespace Msouto\NovaFlexibleContent;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use Msouto\NovaFlexibleContent\Commands\CreateCast;
use Msouto\NovaFlexibleContent\Commands\CreateLayout;
use Msouto\NovaFlexibleContent\Commands\CreatePreset;
use Msouto\NovaFlexibleContent\Commands\CreateResolver;
use Msouto\NovaFlexibleContent\Http\Middleware\InterceptFlexibleAttributes;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->addMiddleware();
      
        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-flexible-content', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-flexible-content', __DIR__.'/../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!$this->app->runningInConsole()) return;

        $this->commands([
            CreateCast::class,
            CreateLayout::class,
            CreatePreset::class,
            CreateResolver::class,
        ]);
    }
    
    /**
     * Adds required middleware for Nova requests.
     *
     * @return void
     */
    public function addMiddleware()
    {
        $router = $this->app['router'];
        
        if ($router->hasMiddlewareGroup('nova')) {
            $router->pushMiddlewareToGroup('nova', InterceptFlexibleAttributes::class);
            
            return;
        }
        
        if (! $this->app->configurationIsCached()) {
            config()->set('nova.middleware', array_merge(
                config('nova.middleware', []),
                [InterceptFlexibleAttributes::class]
            ));
        }
    }
}
