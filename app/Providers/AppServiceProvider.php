<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\LabTest;
use App\Models\Patient;
use App\Observers\AppointmentObserver;
use App\Observers\LabTestObserver;
use App\Observers\PatientObserver;
use App\Policies\AppointmentPolicy;
use App\Policies\LabTestPolicy;
use App\Policies\PatientPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        // The whole UI is built in Bootstrap 5 (per spec), so make
        // every ->links() call render Bootstrap 5 pagination markup
        // instead of Laravel's Tailwind default.
        Paginator::useBootstrapFive();

        Patient::observe(PatientObserver::class);
        Appointment::observe(AppointmentObserver::class);
        LabTest::observe(LabTestObserver::class);

        // Explicit policy registration (in addition to Laravel's
        // naming-convention auto-discovery) so it's obvious where to
        // look when editing authorization rules later.
        Gate::policy(Patient::class, PatientPolicy::class);
        Gate::policy(Appointment::class, AppointmentPolicy::class);
        Gate::policy(LabTest::class, LabTestPolicy::class);
    }
}
