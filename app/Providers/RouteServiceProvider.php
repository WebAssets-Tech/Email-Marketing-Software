<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider {
    /**
     * The path to the "home" route for your application.
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * If specified, this namespace is automatically applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = null;

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot() {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php')); // Load the web routes

            Route::middleware('web')
                ->group(base_path('routes/default.php')); // Load the default routes

            Route::middleware('web')
                ->group(base_path('routes/email.php')); // Load the email routes

            Route::middleware('web')
                ->group(base_path('routes/campaign.php')); // Load the campaign routes
            Route::middleware('web')
                ->group(base_path('routes/cron.php')); // Load the campaign routes

            Route::middleware('web')
                ->group(base_path('routes/group.php')); // Load the group routes

            Route::middleware('web')
                ->group(base_path('routes/builder.php')); // Load the builder routes

            Route::middleware('web')
                ->group(base_path('routes/queue.php')); // Load the queue routes

            Route::middleware('web')
                ->group(base_path('routes/log.php')); // Load the log routes

            Route::middleware('web')
                ->group(base_path('routes/report.php')); // Load the report routes

            Route::middleware('web')
                ->group(base_path('routes/sms.php')); // Load the sms routes

            Route::middleware('web')
                ->group(base_path('routes/subscription.php')); // Load the subscription routes

            Route::middleware('web')
                ->group(base_path('routes/paypal.php')); // Load the paypal routes

            Route::middleware('web')
                ->group(base_path('routes/stripe.php')); // Load the stripe routes

            Route::middleware('web')
                ->group(base_path('routes/paytm.php')); // Load the Paytm routes

            Route::middleware('web')
                ->group(base_path('routes/limit.php')); // Load the limit routes

            Route::middleware('web')
                ->group(base_path('routes/notes.php')); // Load the notes routes

            Route::middleware('web')
                ->group(base_path('routes/currency.php')); // Load the currency routes

            Route::middleware('web')
                ->group(base_path('routes/bounce.php')); // Load the bounce routes

            Route::middleware('web')
                ->group(base_path('routes/campaignlog.php')); // Load the campaignlog routes

            Route::middleware('web')
                ->group(base_path('routes/notify.php')); // Load the notify routes

            Route::middleware('web')
                ->group(base_path('routes/frontend.php')); // Load the frontend routes

            Route::middleware('web')
                ->group(base_path('routes/install.php')); // Load the install routes

            Route::middleware('web')
                ->group(base_path('routes/tracker.php')); // Load the tracker routes

            Route::middleware('web')
                ->group(base_path('routes/voice.php')); // Load the voice routes

            Route::middleware('web')
                ->group(base_path('routes/autoresponder.php')); // Load the autoresponder routes

            Route::middleware('web')
                ->group(base_path('routes/khalti.php')); // Load the khalti routes

            Route::middleware('web')
                ->group(base_path('routes/marketplace.php')); // Load the marketplace routes

            Route::middleware('web')
                ->group(base_path('routes/update.php')); // Load the update routes

            Route::middleware('web')
                ->group(base_path('routes/blog.php')); // Load the blog routes

            Route::middleware('web')
                ->group(base_path('routes/flutterwave.php')); // Load the flutterwave routes

            Route::middleware('web')
                ->group(base_path('routes/instamojo.php')); // Load the instamojo routes

            Route::middleware('web')
                ->group(base_path('routes/paystack.php')); // Load the paystack routes

            Route::middleware('web')
                ->group(base_path('routes/coupon.php')); // Load the coupon routes

            Route::middleware('web')
                ->group(base_path('routes/razorpay.php')); // Load the razorpay routes

            Route::middleware('web')
                ->group(base_path('routes/mollie.php')); // Load the mollie routes

            Route::middleware('web')
                ->group(base_path('routes/agent.php')); // Load the agent routes

            Route::middleware('web')
                ->group(base_path('routes/support.php')); // Load the support routes

            Route::middleware('web')
                ->group(base_path('routes/perfex.php')); // Load the perfex CRM routes

            Route::middleware('web')
                ->group(base_path('routes/wordpress.php')); // Load the WordPress routes

            Route::middleware('web')
                ->group(base_path('routes/activecms.php')); // Load the Active cms routes

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php')); // Load the api routes
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting() {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);
        });
    }
}
