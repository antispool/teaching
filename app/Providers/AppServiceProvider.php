<?php

namespace App\Providers;

use App\Entities\College;
use App\Entities\Student;
use App\Repositories\StudentRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StudentRepository::class, function($app) {
            return new DoctrineStudentRepository(
                $app['em'],
                $app['em']->getClassMetaData(Student::class)
            );
        });
        $this->app->bind(CollegeRepository::class, function($app) {
            return new DoctrineCollegeRepository(
                $app['em'],
                $app['em']->getClassMetaData(College::class)
            );
        });
    }
}
