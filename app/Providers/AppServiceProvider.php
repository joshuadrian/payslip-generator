<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Dedoc\Scramble\Scramble;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });

        Activity::saving(function (Activity $activity) {
            $requestId = request()->header('X-Request-ID') ?? Str::uuid()->toString();
            $activity->properties = $activity->properties->merge([
                'ip' => request()->ip(),
                'request_id' => $requestId,
            ]);
        });
    }
}
