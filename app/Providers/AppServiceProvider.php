<?php

namespace App\Providers;

use App\Models\Quiz;
use App\repositories\Contracts\AnswerSheetRepositoryInterface;
use App\repositories\Contracts\CategoryRepositoryInterface;
use App\repositories\Contracts\QuestionRepositoryInterface;
use App\repositories\Contracts\QuizRepositoryInterface;
use App\repositories\Contracts\UserRepositoryInterface;
use App\repositories\Eloquent\EloquentAnswerSheetRepository;
use App\repositories\Eloquent\EloquentCategoryRepository;
use App\repositories\Eloquent\EloquentQuestionRepository;
use App\repositories\Eloquent\EloquentQuizRepository;
use App\repositories\Eloquent\EloquentUserRepository;
use App\repositories\Json\JsonUserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    public function boot() {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, EloquentCategoryRepository::class);
        $this->app->bind(QuizRepositoryInterface::class, EloquentQuizRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, EloquentQuestionRepository::class);
        $this->app->bind(AnswerSheetRepositoryInterface::class, EloquentAnswerSheetRepository::class);


    }
}
