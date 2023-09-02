<?php

namespace App\repositories\Eloquent;

use App\Entities\Question\QuestionEloquentEntity;
use App\Models\Question;
use App\repositories\Contracts\QuestionRepositoryInterface;

class EloquentQuestionRepository extends EloquentBaseRepository implements QuestionRepositoryInterface
{
    protected $model = Question::class;
    public function create(array $data)
    {
        $createdQuestion = parent::create($data);
        return new QuestionEloquentEntity($createdQuestion);
    }
    public function update(int $id , array $data) {
        if(!parent::update($id , $data)) {
            throw new  \RuntimeException('سوال بروزرسانی نشد');
        }
        return new QuestionEloquentEntity(parent::find($id));
    }
}
