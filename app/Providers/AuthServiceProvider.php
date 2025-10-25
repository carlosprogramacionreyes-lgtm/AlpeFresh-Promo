<?php

namespace App\Providers;

use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('view-reports', function (User $user): bool {
            return $user->isAdmin()
                || $user->isSupervisor()
                || $user->isAnalista();
        });

        Gate::define('config-manage', function (User $user): bool {
            return $user->isAdmin()
                || $user->isSupervisor();
        });

        Gate::define('evaluate', function (User $user): bool {
            return $user->isAdmin()
                || $user->isSupervisor()
                || $user->isPromotor();
        });

        Gate::define('edit-evaluation', function (User $user, Evaluation $evaluation): bool {
            if ($user->isAdmin() || $user->isSupervisor()) {
                return true;
            }

            $isAuthor = $evaluation->user_id === $user->id;
            $isPending = $evaluation->status !== 'submitted';

            return $isAuthor && $isPending;
        });
    }
}
