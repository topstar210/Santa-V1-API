<?php

namespace App\Providers;

use App\Auth\CodeUserProvider;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Auth::provider('code', function($app, array $config) {
            return new CodeUserProvider($app['hash'], $config['model']);
        });

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                        ->subject('Verify Email Address')
                        ->line('Click the button below to verify your email address.')
                        ->action('Verify Email Address', $url)
                        ->line('If you did not create an account, no further action is required.');
        });
    }
}
